<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuestCheckInController extends Controller
{
    public function show($token)
    {
        $reservation = Reservation::where('checkin_token', $token)->firstOrFail();
        return view('guest.checkin', compact('reservation'));
    }

    public function store(Request $request, $token)
    {
        $reservation = Reservation::where('checkin_token', $token)->firstOrFail();

        $request->validate([
            'ktp_photo' => 'required|image|max:2048',
        ]);

        $path = $request->file('ktp_photo')->store('ktp_photos', 'public');

        $reservation->guest->update([
            'ktp_photo_path' => $path,
        ]);

        // Update status reservasi dan status kamar
        $reservation->update(['status' => 'checked_in']);
        $reservation->room->update(['status' => 'occupied']);

        return view('guest.success');
    }
}
