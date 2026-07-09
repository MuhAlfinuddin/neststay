<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * Display report dashboard with charts and metrics.
     */
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $payments = Payment::whereYear('payment_date', $year)
            ->whereIn('payment_status', ['paid', 'down_payment'])
            ->get();

        $totalRevenue = $payments->sum('amount');
        $totalReservations = Reservation::whereYear('created_at', $year)->count();
        $totalRooms = Room::count();
        $activeReservationsCount = Reservation::where('status', 'active')->count();

        // Prepare chart data
        $monthLabels = [];
        $chartRevenueData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthLabels[] = Carbon::create()->month($i)->format('M');
            $chartRevenueData[] = $payments->filter(function ($payment) use ($i) {
                return Carbon::parse($payment->payment_date)->month == $i;
            })->sum('amount');
        }

        return view('reports.index', compact('payments', 'totalRevenue', 'totalReservations', 'totalRooms', 'activeReservationsCount', 'year', 'monthLabels', 'chartRevenueData'));
    }

    /**
     * Export monthly revenue to PDF.
     */
    public function exportPdf()
    {
        $user = auth()->user();
        $homestay = $user->homestay;

        // Plan limitation check
        if ($homestay->plan !== 'lengkap' || $homestay->subscription_status !== 'active') {
            return redirect()->route('dashboard')->with('error', 'Fitur Export PDF hanya tersedia untuk Paket Lengkap.');
        }

        $payments = Payment::with(['reservation.guest', 'reservation.room'])
            ->where('payment_status', 'paid')
            ->orderBy('payment_date', 'desc')
            ->get();

        $data = [
            'payments' => $payments,
            'totalRevenue' => $payments->sum('amount'),
            'date' => Carbon::now()->format('d M Y')
        ];

        $pdf = Pdf::loadView('reports.pdf', $data);
        return $pdf->download('Laporan-Keuangan-'.Carbon::now()->format('Y-m-d').'.pdf');
    }
}
