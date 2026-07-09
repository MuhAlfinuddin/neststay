@extends('layouts.app')

@section('header_title', 'Laporan Analisis Bisnis')

@section('content')
<div class="space-y-8">
    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Laporan Keuangan & Okupansi</h1>
            <p class="text-xs text-slate-500 mt-1">Analisis kinerja bisnis homestay Anda berdasarkan data historis transaksi.</p>
        </div>

        <!-- Year filter -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('reports.pdf') }}" class="px-4 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition">
                Export PDF
            </a>
            <form action="{{ route('reports.index') }}" method="GET" class="flex items-center space-x-2">
                <label for="year" class="text-xs font-semibold text-slate-400 uppercase">Tahun:</label>
                <select id="year" name="year" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)]">
                    @for ($y = date('Y') - 2; $y <= date('Y') + 2; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <!-- Cards Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</span>
            <h3 class="text-2xl font-black text-slate-900 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>

        <!-- Total Bookings -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Reservasi</span>
            <h3 class="text-2xl font-black text-slate-900 mt-2">{{ $totalReservations }}</h3>
        </div>

        <!-- Total Rooms -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kamar</span>
            <h3 class="text-2xl font-black text-slate-900 mt-2">{{ $totalRooms }}</h3>
        </div>

        <!-- Active Reservations -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Reservasi Aktif</span>
            <h3 class="text-2xl font-black text-slate-900 mt-2">{{ $activeReservationsCount }}</h3>
        </div>
    </div>

    <!-- Chart Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Line Chart Panel (2 cols) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-6">Grafik Pendapatan Bulanan ({{ $year }})</h3>
            <div class="relative h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Latest Sales Log (1 col) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Aktivitas Transaksi Terakhir</h3>
            <div class="flex-grow overflow-y-auto space-y-4 max-h-80 pr-2">
                @if ($payments->isEmpty())
                    <p class="text-slate-400 text-center py-12 text-sm">Belum ada transaksi pembayaran lunas.</p>
                @else
                    @foreach ($payments as $pay)
                        <div class="flex justify-between items-center p-3 border border-slate-50 bg-slate-50/20 rounded-xl">
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $pay->reservation->guest->name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">Kamar {{ $pay->reservation->room->room_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-indigo-600">Rp {{ number_format($pay->amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthLabels) !!},
                datasets: [{
                    label: 'Pendapatan (Rupiah)',
                    data: {!! json_encode($chartRevenueData) !!},
                    borderColor: 'rgb(79, 70, 229)',
                    backgroundColor: 'rgba(79, 70, 229, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: 'rgb(79, 70, 229)',
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString('id-ID'),
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
