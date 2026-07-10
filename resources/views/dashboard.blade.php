@extends('layouts.app')

@section('header_title', 'Dasbor Homestay')

@section('content')
<div class="space-y-8">
    {{-- Payment Notification Banner --}}
    @if (isset($homestay) && $homestay->subscription_status !== 'active')
        <div class="p-4 mb-6 rounded-xl border border-amber-200 bg-amber-50 text-amber-800">
            <div class="flex items-center space-x-4">
                <div class="text-2xl">⚠️</div>
                <div>
                    <p class="font-bold">Paket '{{ ucfirst($homestay->plan) }}' Anda menunggu pembayaran.</p>
                    <p class="text-sm">Silakan selesaikan pembayaran untuk mengaktifkan semua fitur dan menghilangkan batasan.</p>
                </div>
                <form action="{{ route('payments.activate') }}" method="POST">
                    @csrf
                    <button type="submit" class="ml-auto whitespace-nowrap inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-full shadow transition transform hover:scale-105">
                        Bayar Sekarang
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Header Welcome -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-[var(--color-teak-deep)]">Selamat Datang di StayNest!</h1>
            <p class="text-xs md:text-sm text-[var(--color-ink-soft)] mt-1">Berikut adalah ikhtisar operasional homestay Anda hari ini.</p>
        </div>
        <div class="flex space-x-3 w-full md:w-auto">
            <a href="{{ route('reservations.create') }}" class="flex-grow md:flex-none inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition shadow-md shadow-[var(--color-teak)]/10">
                + Reservasi Baru
            </a>
            <a href="{{ route('rooms.create') }}" class="flex-grow md:flex-none inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-[var(--color-teak-deep)] bg-[var(--color-paper)] border border-[var(--color-paper-deep)] hover:bg-[var(--color-paper-deep)] rounded-xl transition">
                + Tambah Kamar
            </a>
        </div>
    </div>

    <!-- Cards Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Revenue Card -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-xs md:text-sm font-semibold text-slate-400">Pendapatan Bulan Ini</span>
                <span class="text-sm md:text-lg">💵</span>
            </div>
            <div class="mt-3 md:mt-4">
                <h3 class="text-xl sm:text-2xl font-black text-slate-950">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</h3>
                <p class="text-[11px] sm:text-xs text-emerald-600 font-medium mt-1">✓ Pembayaran berhasil dicatat</p>
            </div>
        </div>

        <!-- Occupancy Rate Card -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-xs md:text-sm font-semibold text-slate-400">Okupansi Kamar</span>
                <span class="text-sm md:text-lg">🛏️</span>
            </div>
            <div class="mt-3 md:mt-4">
                <h3 class="text-xl sm:text-2xl font-black text-slate-950">
                    {{ $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0 }}%
                </h3>
                <p class="text-[11px] sm:text-xs text-slate-500 mt-1">{{ $occupiedRooms }} terisi dari {{ $totalRooms }} total kamar</p>
            </div>
        </div>

        <!-- Active Bookings Card -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-xs md:text-sm font-semibold text-slate-400">Reservasi Aktif</span>
                <span class="text-sm md:text-lg">📅</span>
            </div>
            <div class="mt-3 md:mt-4">
                <h3 class="text-xl sm:text-2xl font-black text-slate-950">{{ $activeReservations }}</h3>
                <p class="text-[11px] sm:text-xs text-slate-500 mt-1">Status Confirmed / Checked In</p>
            </div>
        </div>

        <!-- Total Guests Card -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-xs md:text-sm font-semibold text-slate-400">Total Tamu Terdaftar</span>
                <span class="text-sm md:text-lg">👥</span>
            </div>
            <div class="mt-3 md:mt-4">
                <h3 class="text-xl sm:text-2xl font-black text-slate-950">{{ $totalGuests }}</h3>
                <p class="text-[11px] sm:text-xs text-indigo-600 font-medium mt-1">Dalam database homestay Anda</p>
            </div>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Room Status Grid (2 cols on large screen) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex flex-col md:flex-row justify-between md:items-center space-y-3 md:space-y-0 mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Visual Status Kamar</h3>
                    <div class="flex flex-wrap gap-2 text-xs font-semibold">
                        <span class="px-2 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100">Tersedia ({{ $availableRooms }})</span>
                        <span class="px-2 py-1 rounded-md bg-red-50 text-red-700 border border-red-100">Terisi ({{ $occupiedRooms }})</span>
                        <span class="px-2 py-1 rounded-md bg-amber-50 text-amber-700 border border-amber-100">Perbaikan ({{ $maintenanceRooms }})</span>
                    </div>
                </div>

                @if ($rooms->isEmpty())
                    <div class="text-center py-12 text-slate-400">
                        <p class="text-lg">Belum ada data kamar.</p>
                        <a href="{{ route('rooms.create') }}" class="text-sm text-[var(--color-teak-deep)] font-bold hover:underline mt-2 inline-block">Tambah kamar pertama Anda</a>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach ($rooms as $room)
                            <div class="p-3 sm:p-4 rounded-xl border relative flex flex-col justify-between h-28 transition hover:border-slate-300
                                @if ($room->status === 'available') bg-emerald-50/20 border-emerald-100 @elseif ($room->status === 'occupied') bg-red-50/20 border-red-100 @else bg-amber-50/20 border-amber-100 @endif">

                                <div class="flex justify-between items-start">
                                    <span class="text-[11px] sm:text-xs font-semibold text-slate-400 uppercase">{{ $room->room_type }}</span>
                                    <span class="w-2.5 h-2.5 rounded-full
                                        @if ($room->status === 'available') bg-emerald-500 @elseif ($room->status === 'occupied') bg-red-500 @else bg-amber-500 @endif">
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <h4 class="text-xl sm:text-2xl font-black text-slate-950">No. {{ $room->room_number }}</h4>
                                    <p class="text-[11px] sm:text-xs text-slate-500 mt-1">Rp {{ number_format($room->price_per_night, 0, ',', '.') }}/malam</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Reservations Table -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Reservasi Terbaru</h3>
                    <a href="{{ route('reservations.index') }}" class="text-xs sm:text-sm font-bold text-[var(--color-teak-deep)] hover:underline">Lihat Semua</a>
                </div>

                @if ($recentReservations->isEmpty())
                    <p class="text-slate-400 text-center py-6 text-sm">Belum ada reservasi masuk.</p>
                @else
                    <div class="overflow-x-auto rounded-lg border border-slate-100">
                        <table class="w-full text-[10px] sm:text-sm">
                            <thead class="bg-slate-50 text-slate-500">
                                <tr class="text-left font-semibold">
                                    <th class="p-2 sm:p-3">Tamu</th>
                                    <th class="p-2 sm:p-3">Kamar</th>
                                    <th class="p-2 sm:p-3 whitespace-nowrap">Check In</th>
                                    <th class="p-2 sm:p-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 font-medium">
                                @foreach ($recentReservations as $res)
                                    <tr>
                                        <td class="p-2 sm:p-3">
                                            <p class="font-bold text-slate-800">{{ $res->guest->name }}</p>
                                        </td>
                                        <td class="p-2 sm:p-3">
                                            <span class="inline-block px-1.5 py-0.5 rounded bg-slate-100 text-slate-700">
                                                No.{{ $res->room->room_number }}
                                            </span>
                                        </td>
                                        <td class="p-2 sm:p-3 whitespace-nowrap text-slate-600">{{ $res->check_in->format('d/m/y') }}</td>
                                        <td class="p-2 sm:p-3">
                                            <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] font-semibold
                                                @if ($res->status === 'checked_in') bg-blue-50 text-blue-700 @elseif ($res->status === 'confirmed') bg-emerald-50 text-emerald-700 @else bg-amber-50 text-amber-700 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $res->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Today Operations (Check-ins & Check-outs) (1 col) -->
        <div class="space-y-6">
            <!-- Today Check Ins -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center justify-between">
                    <span>Kedatangan Hari Ini</span>
                    <span class="px-2 py-0.5 rounded text-xs bg-[var(--color-leaf)]/10 text-[var(--color-leaf-deep)]">{{ $todayCheckins->count() }} Tamu</span>
                </h3>

                @if ($todayCheckins->isEmpty())
                    <p class="text-slate-400 text-center py-6 text-sm">Tidak ada kedatangan tamu hari ini.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($todayCheckins as $checkin)
                            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $checkin->guest->name }}</p>
                                    <p class="text-xs text-slate-500 mt-1">Kamar {{ $checkin->room->room_number }} ({{ $checkin->room->room_type }})</p>
                                </div>
                                <form action="{{ route('reservations.check-in', $checkin->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-[var(--color-teak-deep)] hover:bg-[var(--color-marigold-deep)] text-white text-xs font-bold rounded-lg transition">
                                        Check In
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Today Check Outs -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center justify-between">
                    <span>Keberangkatan Hari Ini</span>
                    <span class="px-2 py-0.5 rounded text-xs bg-[var(--color-marigold)]/20 text-[var(--color-marigold-deep)]">{{ $todayCheckouts->count() }} Tamu</span>
                </h3>

                @if ($todayCheckouts->isEmpty())
                    <p class="text-slate-400 text-center py-6 text-sm">Tidak ada keberangkatan tamu hari ini.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($todayCheckouts as $checkout)
                            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                <div>
                                    <p class="font-bold">{{ $checkout->guest->name }}</p>
                                    <p class="text-xs text-slate-500 mt-1">Kamar {{ $checkout->room->room_number }} ({{ $checkout->room->room_type }})</p>
                                </div>
                                <form action="{{ route('reservations.check-out', $checkout->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-[var(--color-teak-deep)] hover:bg-[var(--color-marigold-deep)] text-white text-xs font-bold rounded-lg transition">
                                        Check Out
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection