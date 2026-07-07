<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the Owner/Staff Dashboard.
     */
    public function index()
    {
        $today = Carbon::today();

        // 1. Core Metrics
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();

        $totalGuests = Guest::count();

        // Active bookings (Confirmed or Checked In)
        $activeReservations = Reservation::whereIn('status', ['confirmed', 'checked_in'])->count();

        // Revenue this month (payments recorded in current month)
        $revenueThisMonth = Payment::whereYear('payment_date', Carbon::now()->year)
            ->whereMonth('payment_date', Carbon::now()->month)
            ->where('payment_status', 'paid')
            ->sum('amount');

        // 2. Lists for Dashboard
        // Check-ins today
        $todayCheckins = Reservation::with(['room', 'guest'])
            ->whereDate('check_in', $today)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        // Check-outs today
        $todayCheckouts = Reservation::with(['room', 'guest'])
            ->whereDate('check_out', $today)
            ->where('status', 'checked_in')
            ->get();

        // Latest bookings
        $recentReservations = Reservation::with(['room', 'guest'])
            ->latest()
            ->limit(5)
            ->get();

        // Room status summary for visual grid
        $rooms = Room::orderBy('room_number')->get();

        // Get homestay plan to show upgrade/payment notifications
        $homestay = auth()->user()->homestay;

        return view('dashboard', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'totalGuests',
            'activeReservations',
            'revenueThisMonth',
            'todayCheckins',
            'todayCheckouts',
            'recentReservations',
            'rooms',
            'homestay' // Pass the homestay model
        ));
    }
}
