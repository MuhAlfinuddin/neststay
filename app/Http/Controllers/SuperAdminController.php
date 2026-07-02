<?php

namespace App\Http\Controllers;

use App\Models\Homestay;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    /**
     * Display Super Admin Dashboard.
     */
    public function dashboard()
    {
        // Enforce Super Admin role!
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Akses khusus Administrator Utama.');
        }

        // Metrics
        $totalHomestaysCount = Homestay::count();
        $activeHomestaysCount = Homestay::where('status', 'active')->count();
        $suspendedHomestaysCount = Homestay::where('status', 'suspended')->count();
        $totalOwnersCount = User::where('role', 'owner')->count();
        $totalStaffsCount = User::where('role', 'staff')->count();

        // Retrieve all homestays along with their owners
        $homestays = Homestay::with(['users' => function($q) {
            $q->where('role', 'owner');
        }])->latest()->paginate(10);

        return view('super_admin.dashboard', compact(
            'totalHomestaysCount',
            'activeHomestaysCount',
            'suspendedHomestaysCount',
            'totalOwnersCount',
            'totalStaffsCount',
            'homestays'
        ));
    }

    /**
     * Toggle Homestay active/suspended status.
     */
    public function toggleStatus(Homestay $homestay)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        $newStatus = $homestay->status === 'active' ? 'suspended' : 'active';
        $homestay->update(['status' => $newStatus]);

        $message = $newStatus === 'active' 
            ? "Homestay '{$homestay->name}' berhasil diaktifkan kembali!" 
            : "Homestay '{$homestay->name}' berhasil ditangguhkan (Suspended)!";

        return back()->with('success', $message);
    }
}
