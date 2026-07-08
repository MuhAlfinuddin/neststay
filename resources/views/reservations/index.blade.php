@extends('layouts.app')

@section('header_title', 'Reservasi Kamar')

@section('content')
<div class="space-y-6">
    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Daftar Reservasi</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola siklus tamu mulai dari booking, check-in, hingga check-out.</p>
        </div>
        <a href="{{ route('reservations.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition shadow-md shadow-indigo-600/10">
            + Buat Reservasi Baru
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
        <form action="{{ route('reservations.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-grow">
                <label for="search" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Cari Tamu</label>
                <input id="search" name="search" type="text" placeholder="Cari nama tamu..." value="{{ request('search') }}" class="appearance-none block w-full px-3 py-2 border border-slate-200 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-xs">
            </div>
            
            <div class="w-full sm:w-48">
                <label for="status" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Status Reservasi</label>
                <select id="status" name="status" class="appearance-none block w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-xs">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="checked_in" {{ request('status') === 'checked_in' ? 'selected' : '' }}>Checked In</option>
                    <option value="checked_out" {{ request('status') === 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="flex space-x-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto px-4 py-2 text-xs font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition">
                    Filter
                </button>
                <a href="{{ route('reservations.index') }}" class="w-full sm:w-auto px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl text-center transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Reservations Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if ($reservations->isEmpty())
            <div class="text-center py-16 text-slate-400">
                <p class="text-lg">Reservasi tidak ditemukan.</p>
                <p class="text-xs mt-1">Coba sesuaikan pencarian Anda atau buat reservasi baru.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead>
                        <tr class="text-left font-semibold text-slate-400 bg-slate-50/50">
                            <th class="px-6 py-4">Tamu</th>
                            <th class="px-6 py-4">Kamar</th>
                            <th class="px-6 py-4">Check In & Out</th>
                            <th class="px-6 py-4">Total Biaya</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi Operasional</th>
                            <th class="px-6 py-4 text-right">Kelola</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-medium text-slate-700">
                        @foreach ($reservations as $res)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900">{{ $res->guest->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $res->guest->phone }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-xs">
                                        Kamar {{ $res->room->room_number }} ({{ $res->room->room_type }})
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-slate-500 font-semibold">CI: {{ $res->check_in->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-500 font-semibold">CO: {{ $res->check_out->format('d M Y') }}</p>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    Rp {{ number_format($res->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if ($res->status === 'checked_in') bg-blue-50 text-blue-700 @elseif ($res->status === 'checked_out') bg-slate-100 text-slate-600 @elseif ($res->status === 'confirmed') bg-emerald-50 text-emerald-700 @elseif ($res->status === 'cancelled') bg-red-50 text-red-700 @else bg-amber-50 text-amber-700 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $res->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-1">
                                    @if ($res->status === 'pending' || $res->status === 'confirmed')
                                        <form action="{{ route('reservations.check-in', $res->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 text-xs font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-lg transition">
                                                Check In
                                            </button>
                                        </form>
                                    @elseif ($res->status === 'checked_in')
                                        <form action="{{ route('reservations.check-out', $res->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 rounded-lg transition">
                                                Check Out
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">Selesai</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-2 text-xs font-bold">
                                    <a href="{{ route('reservations.edit', $res->id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 transition">Edit</a>
                                    <form action="{{ route('reservations.destroy', $res->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data reservasi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-50">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
