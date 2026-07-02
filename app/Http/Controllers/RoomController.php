<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::query();

        // Search & Filter
        if ($request->filled('search')) {
            $query->where('room_number', 'like', '%' . $request->search . '%')
                  ->orWhere('room_type', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rooms = $query->orderBy('room_number')->paginate(10);

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => ['required', 'string', 'max:50'],
            'room_type' => ['required', 'string', 'max:100'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:available,occupied,maintenance'],
            'description' => ['nullable', 'string'],
        ]);

        // Validate uniqueness within the current homestay context!
        // TenantScope automatically applies, so checking Room::where('room_number') checks within this homestay.
        if (Room::where('room_number', $request->room_number)->exists()) {
            return back()->withErrors(['room_number' => 'Nomor kamar sudah terdaftar di homestay ini.'])->withInput();
        }

        // Retrieve homestay_id from current auth user
        $homestayId = auth()->user()->homestay_id;

        Room::create(array_merge($request->all(), ['homestay_id' => $homestayId]));

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => ['required', 'string', 'max:50'],
            'room_type' => ['required', 'string', 'max:100'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:available,occupied,maintenance'],
            'description' => ['nullable', 'string'],
        ]);

        // Validate uniqueness except self
        if (Room::where('room_number', $request->room_number)->where('id', '!=', $room->id)->exists()) {
            return back()->withErrors(['room_number' => 'Nomor kamar sudah terdaftar di homestay ini.'])->withInput();
        }

        $room->update($request->all());

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil diperbarui!');
    }

    public function destroy(Room $room)
    {
        // Check if there are active bookings in this room
        if ($room->reservations()->whereIn('status', ['confirmed', 'checked_in'])->exists()) {
            return back()->with('error', 'Gagal menghapus kamar. Kamar sedang memiliki reservasi aktif!');
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil dihapus!');
    }
}
