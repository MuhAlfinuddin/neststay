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
        <form action="{{ route('reports.index') }}" method="GET" class="flex items-center space-x-2">
            <label for="year" class="text-xs font-semibold text-slate-400 uppercase">Tahun:</label>
            <select id="year" name="year" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @for ($y = date('Y') - 2; $y <= date('Y') + 2; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    <!-- Cards Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Total Revenue -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan Terkumpul</span>
            <h3 class="text-3xl font-black text-slate-900 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-400 mt-2">Akumulasi dari seluruh transaksi status Lunas (Paid).</p>
        </div>

        <!-- Total Bookings -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Reservasi Terdaftar</span>
            <h3 class="text-3xl font-black text-slate-900 mt-2">{{ $totalReservations }} Kali Booking</h3>
            <p class="text-xs text-slate-400 mt-2">Termasuk status check-in, check-out, pending, dan confirmed.</p>
        </div>

        <!-- Total Rooms -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kapasitas Kamar</span>
            <h3 class="text-3xl font-black text-slate-900 mt-2">{{ $totalRooms }} Unit Kamar</h3>
            <p class="text-xs text-slate-400 mt-2">Aset aktif yang terdaftar dalam inventaris homestay.</p>
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
                                <p class="text-xs text-slate-400 mt-0.5">Kamar {{ $pay->reservation->room->room_number }} • {{ $pay->payment_method }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-indigo-600">Rp {{ number_format($pay->amount, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-slate-400">{{ $pay->payment_date->format('d M H:i') }}</p>
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
        const chart = new Chart(ctx, {
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
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            },
                            font: {
                                family: "'Plus Jakarta Sans', sans-serif",
                                size: 10
                            }
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: "'Plus Jakarta Sans', sans-serif",
                                size: 10
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
