<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display a listing of staff under the Owner's homestay.
     */
    public function index()
    {
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Akses khusus Pemilik (Owner) homestay.');
        }

        $homestayId = auth()->user()->homestay_id;

        // Retrieve other users belonging to the same homestay having 'staff' role
        $staffs = User::where('homestay_id', $homestayId)
            ->where('role', 'staff')
            ->latest()
            ->paginate(10);

        return view('staff.index', compact('staffs'));
    }

    /**
     * Show form to create new staff.
     */
    public function create()
    {
        if (auth()->user()->role !== 'owner') {
            abort(403);
        }

        $user = auth()->user();
        $homestay = $user->homestay;
        $staffCount = $homestay->users()->where('role', 'staff')->count();

        // Plan limitation check
        if ($homestay->subscription_status !== 'active') {
            if ($staffCount >= 1) {
                return redirect()->route('staff.index')->with('error', 'Gagal menambah staff. Silakan selesaikan pembayaran untuk menambah staff lebih dari 1.');
            }
        } elseif ($homestay->plan === 'hemat' && $staffCount >= 2) {
            return redirect()->route('staff.index')->with('error', 'Gagal menambah staff. Paket Hemat hanya mendukung maksimal 2 akun staff.');
        }

        return view('staff.create');
    }

    /**
     * Store new staff in the database.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'owner') {
            abort(403);
        }

        $user = auth()->user();
        $homestay = $user->homestay;
        $staffCount = $homestay->users()->where('role', 'staff')->count();

        // Plan limitation check (server-side security)
        if ($homestay->subscription_status !== 'active') {
            if ($staffCount >= 1) {
                return back()->with('error', 'Gagal menambah staff. Silakan selesaikan pembayaran untuk menambah staff lebih dari 1.');
            }
        } elseif ($homestay->plan === 'hemat' && $staffCount >= 2) {
            return back()->with('error', 'Gagal menambah staff. Paket Hemat hanya mendukung maksimal 2 akun staff.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'homestay_id' => $homestay->id,
        ]);

        return redirect()->route('staff.index')->with('success', 'Akun Staff berhasil dibuat!');
    }

    /**
     * Remove staff account.
     */
    public function destroy(User $staff)
    {
        if (auth()->user()->role !== 'owner') {
            abort(403);
        }

        if ($staff->homestay_id !== auth()->user()->homestay_id || $staff->role !== 'staff') {
            abort(403, 'Tindakan ilegal.');
        }

        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Akun Staff berhasil dihapus!');
    }
}
