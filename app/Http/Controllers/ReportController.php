<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display report dashboard with charts and metrics.
     */
    public function index(Request $request)
    {
        $year = $request->query('year', Carbon::now()->year);

        // 1. Overall stats
        $totalRooms = Room::count();
        $totalReservations = Reservation::count();
        $totalRevenue = Payment::where('payment_status', 'paid')->sum('amount');
        
        // 2. Monthly Revenue Data for Chart (current year)
        $driver = DB::connection()->getDriverName();
        $monthExpr = $driver === 'mysql'
            ? DB::raw("DATE_FORMAT(payment_date, '%m') as month")
            : DB::raw("strftime('%m', payment_date) as month");

        $monthlyRevenue = Payment::select(
                $monthExpr,
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('payment_date', $year)
            ->where('payment_status', 'paid')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Standardize monthly array for Chart.js
        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $chartRevenueData = [];
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

        foreach ($months as $month) {
            $chartRevenueData[] = $monthlyRevenue[$month] ?? 0;
        }

        // 3. Occupancy Rate Data
        // Total occupied days = Sum of diff in days for check-in and check-out in this year
        // We will calculate a simple occupancy check:
        $activeReservationsCount = Reservation::whereIn('status', ['confirmed', 'checked_in', 'checked_out'])->count();
        
        // 4. Recent transaction logs
        $payments = Payment::with(['reservation.guest'])
            ->where('payment_status', 'paid')
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();

        return view('reports.index', compact(
            'year',
            'totalRooms',
            'totalReservations',
            'totalRevenue',
            'chartRevenueData',
            'monthLabels',
            'activeReservationsCount',
            'payments'
        ));
    }
}
