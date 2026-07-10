<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['reservation.guest', 'reservation.room']);

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        $payments = $query->latest()->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        // Fetch reservations that need payments (e.g. status isn't cancelled)
        $reservations = Reservation::with(['guest', 'room'])
            ->where('status', '!=', 'cancelled')
            ->orderBy('id', 'desc')
            ->get();

        $selectedReservationId = $request->query('reservation_id');

        return view('payments.create', compact('reservations', 'selectedReservationId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => ['required', 'exists:reservations,id'],
            'amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $reservation = Reservation::findOrFail($request->reservation_id);
                    if ($value > $reservation->total_price) {
                        $fail('Jumlah pembayaran tidak boleh melebihi total tagihan (Rp ' . number_format($reservation->total_price, 0, ',', '.') . ').');
                    }
                },
            ],
            'payment_method' => ['required', 'string', 'in:cash,transfer,card,qris'],
            'payment_status' => ['required', 'string', 'in:paid,down_payment,unpaid'],
            'payment_date' => ['required', 'date'],
        ]);

        $homestayId = auth()->user()->homestay_id;

        $payment = Payment::create([
            'homestay_id' => $homestayId,
            'reservation_id' => $request->reservation_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'payment_date' => $request->payment_date,
        ]);

        // Automatically update reservation status if full payment is paid
        if ($request->payment_status === 'paid') {
            $reservation = Reservation::findOrFail($request->reservation_id);
            if ($reservation->status === 'pending' && $request->amount >= $reservation->total_price) {
                $reservation->update(['status' => 'confirmed']);
            }
        }

        return redirect()->route('payments.index')->with('success', 'Transaksi pembayaran berhasil dicatat!');
    }

    /**
     * Show Invoice Receipt.
     */
    public function show(Payment $payment)
    {
        $payment->load(['reservation.guest', 'reservation.room', 'homestay']);
        return view('payments.show', compact('payment'));
    }

    public function activateSubscription(Request $request)
    {
        $user = auth()->user();
        $homestay = $user->homestay;

        if ($homestay) {
            $homestay->subscription_status = 'active';
            $homestay->save(); // Menggunakan save() agar lebih aman
            return redirect()->route('dashboard')->with('success', 'Paket berhasil diaktifkan!');
        }
        return back()->with('error', 'Gagal mengaktifkan paket.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Catatan pembayaran berhasil dihapus!');
    }
}
