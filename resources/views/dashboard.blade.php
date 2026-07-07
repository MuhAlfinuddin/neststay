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
            <h1 class="text-3xl font-extrabold text-slate-900">Selamat Datang di StayNest!</h1>
            <p class="text-sm text-slate-500 mt-1">Berikut adalah ikhtisar operasional homestay Anda hari ini.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('reservations.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md shadow-indigo-600/10">
                + Reservasi Baru
            </a>
            <a href="{{ route('rooms.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition">
                + Tambah Kamar
            </a>
        </div>
    </div>

    <!-- Cards Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Revenue Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-sm font-semibold text-slate-400">Pendapatan Bulan Ini</span>
                <span class="text-lg">💵</span>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-black text-slate-950">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</h3>
                <p class="text-xs text-emerald-600 font-medium mt-1">✓ Pembayaran berhasil dicatat</p>
            </div>
        </div>

        <!-- Occupancy Rate Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-sm font-semibold text-slate-400">Okupansi Kamar</span>
                <span class="text-lg">🛏️</span>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-black text-slate-950">
                    {{ $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0 }}%
                </h3>
                <p class="text-xs text-slate-500 mt-1">{{ $occupiedRooms }} terisi dari {{ $totalRooms }} total kamar</p>
            </div>
        </div>

        <!-- Active Bookings Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-sm font-semibold text-slate-400">Reservasi Aktif</span>
                <span class="text-lg">📅</span>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-black text-slate-950">{{ $activeReservations }}</h3>
                <p class="text-xs text-slate-500 mt-1">Status Confirmed / Checked In</p>
            </div>
        </div>

        <!-- Total Guests Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <span class="text-sm font-semibold text-slate-400">Total Tamu Terdaftar</span>
                <span class="text-lg">👥</span>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-black text-slate-950">{{ $totalGuests }}</h3>
                <p class="text-xs text-indigo-600 font-medium mt-1">Dalam database homestay Anda</p>
            </div>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Room Status Grid (2 cols on large screen) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Visual Status Kamar</h3>
                    <div class="flex space-x-2 text-xs font-semibold">
                        <span class="px-2 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100">Tersedia ({{ $availableRooms }})</span>
                        <span class="px-2 py-1 rounded-md bg-red-50 text-red-700 border border-red-100">Terisi ({{ $occupiedRooms }})</span>
                        <span class="px-2 py-1 rounded-md bg-amber-50 text-amber-700 border border-amber-100">Perbaikan ({{ $maintenanceRooms }})</span>
                    </div>
                </div>

                @if ($rooms->isEmpty())
                    <div class="text-center py-12 text-slate-400">
                        <p class="text-lg">Belum ada data kamar.</p>
                        <a href="{{ route('rooms.create') }}" class="text-sm text-indigo-600 font-bold hover:underline mt-2 inline-block">Tambah kamar pertama Anda</a>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach ($rooms as $room)
                            <div class="p-4 rounded-xl border relative flex flex-col justify-between h-28 transition hover:border-slate-300
                                @if ($room->status === 'available') bg-emerald-50/20 border-emerald-100 @elseif ($room->status === 'occupied') bg-red-50/20 border-red-100 @else bg-amber-50/20 border-amber-100 @endif">

                                <div class="flex justify-between items-start">
                                    <span class="text-xs font-semibold text-slate-400 uppercase">{{ $room->room_type }}</span>
                                    <span class="w-2.5 h-2.5 rounded-full
                                        @if ($room->status === 'available') bg-emerald-500 @elseif ($room->status === 'occupied') bg-red-500 @else bg-amber-500 @endif">
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <h4 class="text-2xl font-black text-slate-950">No. {{ $room->room_number }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">Rp {{ number_format($room->price_per_night, 0, ',', '.') }}/malam</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Reservations Table -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Reservasi Terbaru</h3>
                    <a href="{{ route('reservations.index') }}" class="text-sm font-bold text-indigo-600 hover:underline">Lihat Semua</a>
                </div>

                @if ($recentReservations->isEmpty())
                    <p class="text-slate-400 text-center py-6 text-sm">Belum ada reservasi masuk.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead>
                                <tr class="text-left font-semibold text-slate-400">
                                    <th class="py-3">Tamu</th>
                                    <th class="py-3">Kamar</th>
                                    <th class="py-3">Check In</th>
                                    <th class="py-3">Check Out</th>
                                    <th class="py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 font-medium">
                                @foreach ($recentReservations as $res)
                                    <tr>
                                        <td class="py-3">
                                            <p class="font-bold text-slate-800">{{ $res->guest->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $res->guest->phone }}</p>
                                        </td>
                                        <td class="py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-xs">
                                                Kamar {{ $res->room->room_number }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-slate-600">{{ $res->check_in->format('d M Y') }}</td>
                                        <td class="py-3 text-slate-600">{{ $res->check_out->format('d M Y') }}</td>
                                        <td class="py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                                @if ($res->status === 'checked_in') bg-blue-50 text-blue-700 @elseif ($res->status === 'checked_out') bg-slate-100 text-slate-600 @elseif ($res->status === 'confirmed') bg-emerald-50 text-emerald-700 @elseif ($res->status === 'cancelled') bg-red-50 text-red-700 @else bg-amber-50 text-amber-700 @endif">
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
                    <span class="px-2 py-0.5 rounded text-xs bg-emerald-50 text-emerald-600">{{ $todayCheckins->count() }} Tamu</span>
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
                                    <button type="submit" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition">
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
                    <span class="px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-600">{{ $todayCheckouts->count() }} Tamu</span>
                </h3>

                @if ($todayCheckouts->isEmpty())
                    <p class="text-slate-400 text-center py-6 text-sm">Tidak ada keberangkatan tamu hari ini.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($todayCheckouts as $checkout)
                            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-checkout->guest->name }}</p>
                                    <p class="text-xs text-slate-500 mt-1">Kamar {{ $checkout->room->room_number }} ({{ $checkout->room->room_type }})</p>
                                </div>
                                <form action="{{ route('reservations.check-out', $checkout->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">
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