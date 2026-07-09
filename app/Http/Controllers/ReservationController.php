<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['room', 'guest']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('guest', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $reservations = $query->latest()->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $rooms = Room::where('status', '!=', 'maintenance')->orderBy('room_number')->get();
        $guests = Guest::orderBy('name')->get();
        return view('reservations.create', compact('rooms', 'guests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_id' => ['required', 'exists:guests,id'],
            'room_id' => ['required', 'exists:rooms,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'status' => ['required', 'string', 'in:pending,confirmed'],
        ]);

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);

        // 1. Overbooking validation check!
        $overlapExists = Reservation::where('room_id', $request->room_id)
            ->where('status', '!=', 'cancelled')
            ->where('check_in', '<', $checkOut->toDateString())
            ->where('check_out', '>', $checkIn->toDateString())
            ->exists();

        if ($overlapExists) {
            return back()->withErrors(['room_id' => 'Kamar ini sudah dipesan pada tanggal yang dipilih (Terjadi bentrok/Overbooking).'])->withInput();
        }

        // 2. Fetch room details to calculate total price
        $room = Room::findOrFail($request->room_id);
        $days = max(1, $checkIn->diffInDays($checkOut));
        $totalPrice = $days * $room->price_per_night;

        $homestayId = auth()->user()->homestay_id;

        Reservation::create([
            'homestay_id' => $homestayId,
            'guest_id' => $request->guest_id,
            'room_id' => $request->room_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_price' => $totalPrice,
            'status' => $request->status,
            'checkin_token' => Str::random(32),
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil dibuat!');
    }

    public function edit(Reservation $reservation)
    {
        $rooms = Room::where('status', '!=', 'maintenance')->orderBy('room_number')->get();
        $guests = Guest::orderBy('name')->get();
        return view('reservations.edit', compact('reservation', 'rooms', 'guests'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'guest_id' => ['required', 'exists:guests,id'],
            'room_id' => ['required', 'exists:rooms,id'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'status' => ['required', 'string', 'in:pending,confirmed,checked_in,checked_out,cancelled'],
        ]);

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);

        // 1. Overbooking validation check (excluding current reservation!)
        $overlapExists = Reservation::where('room_id', $request->room_id)
            ->where('id', '!=', $reservation->id)
            ->where('status', '!=', 'cancelled')
            ->where('check_in', '<', $checkOut->toDateString())
            ->where('check_out', '>', $checkIn->toDateString())
            ->exists();

        if ($overlapExists) {
            return back()->withErrors(['room_id' => 'Kamar ini sudah dipesan pada tanggal yang dipilih (Terjadi bentrok/Overbooking).'])->withInput();
        }

        // 2. Fetch room details to calculate total price
        $room = Room::findOrFail($request->room_id);
        $days = max(1, $checkIn->diffInDays($checkOut));
        $totalPrice = $days * $room->price_per_night;

        // 3. Update reservation
        $reservation->update([
            'guest_id' => $request->guest_id,
            'room_id' => $request->room_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_price' => $totalPrice,
            'status' => $request->status,
        ]);

        // 4. Update room status based on reservation status
        $this->updateRoomStatus($reservation->room);

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil diperbarui!');
    }

    /**
     * Perform Check-in action.
     */
    public function checkIn(Reservation $reservation)
    {
        if ($reservation->status !== 'confirmed' && $reservation->status !== 'pending') {
            return back()->with('error', 'Check-in hanya dapat dilakukan pada reservasi status Pending atau Confirmed.');
        }

        $reservation->update(['status' => 'checked_in']);
        
        // Mark room as occupied
        $reservation->room->update(['status' => 'occupied']);

        return back()->with('success', 'Tamu berhasil check-in! Status kamar diset menjadi Terisi (Occupied).');
    }

    /**
     * Perform Check-out action.
     */
    public function checkOut(Reservation $reservation)
    {
        if ($reservation->status !== 'checked_in') {
            return back()->with('error', 'Check-out hanya dapat dilakukan pada tamu yang sedang menginap (Checked In).');
        }

        $reservation->update(['status' => 'checked_out']);
        
        // Recalculate room status based on all active reservations
        $this->updateRoomStatus($reservation->room);

        return back()->with('success', 'Tamu berhasil check-out! Status kamar diset menjadi Tersedia (Available).');
    }

    public function destroy(Reservation $reservation)
    {
        $room = $reservation->room;
        $reservation->delete();

        // Recalculate room status
        $this->updateRoomStatus($room);

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil dihapus!');
    }

    /**
     * Helper to keep room status synchronized with database reservation realities.
     */
    protected function updateRoomStatus($room)
    {
        // If there's anyone currently Checked In to this room, it is occupied
        $hasActiveCheckin = Reservation::where('room_id', $room->id)
            ->where('status', 'checked_in')
            ->exists();

        if ($hasActiveCheckin) {
            $room->update(['status' => 'occupied']);
        } else {
            // Only make available if it's not marked maintenance
            if ($room->status !== 'maintenance') {
                $room->update(['status' => 'available']);
            }
        }
    }
}
