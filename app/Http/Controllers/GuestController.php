<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $query = Guest::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('identity_number', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $guests = $query->latest()->paginate(10);

        return view('guests.index', compact('guests'));
    }

    public function create()
    {
        return view('guests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'identity_number' => ['required', 'string', 'max:50'],
        ]);

        $homestayId = auth()->user()->homestay_id;

        Guest::create(array_merge($request->all(), ['homestay_id' => $homestayId]));

        return redirect()->route('guests.index')->with('success', 'Data tamu berhasil ditambahkan!');
    }

    public function edit(Guest $guest)
    {
        return view('guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'identity_number' => ['required', 'string', 'max:50'],
        ]);

        $guest->update($request->all());

        return redirect()->route('guests.index')->with('success', 'Data tamu berhasil diperbarui!');
    }

    public function destroy(Guest $guest)
    {
        // Check if guest has active/uncompleted bookings
        if ($guest->reservations()->whereIn('status', ['confirmed', 'checked_in'])->exists()) {
            return back()->with('error', 'Gagal menghapus tamu. Tamu sedang memiliki reservasi aktif!');
        }

        $guest->delete();

        return redirect()->route('guests.index')->with('success', 'Data tamu berhasil dihapus!');
    }
}
