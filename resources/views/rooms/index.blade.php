@extends('layouts.app')

@section('header_title', 'Kelola Kamar')

@section('content')
<div class="space-y-6">
    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Daftar Kamar</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola ketersediaan, jenis, dan harga kamar homestay Anda.</p>
        </div>
        <a href="{{ route('rooms.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition shadow-md shadow-indigo-600/10">
            + Tambah Kamar Baru
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
        <form action="{{ route('rooms.index') }}" method="GET" class="flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-grow">
                    <label for="search" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Cari Kamar</label>
                    <input id="search" name="search" type="text" placeholder="Cari nomor atau tipe kamar..." value="{{ request('search') }}" class="appearance-none block w-full px-3 py-2 border border-slate-200 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-xs">
                </div>

                <div class="w-full sm:w-48">
                    <label for="status" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Status</label>
                    <select id="status" name="status" class="appearance-none block w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-xs">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia (Available)</option>
                        <option value="occupied" {{ request('status') === 'occupied' ? 'selected' : '' }}>Terisi (Occupied)</option>
                        <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Perbaikan (Maintenance)</option>
                    </select>
                </div>
            </div>

            <div class="flex space-x-2 justify-start w-full">
                <button type="submit" class="px-6 py-2 text-xs font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition">
                    Filter
                </button>
                <a href="{{ route('rooms.index') }}" class="px-6 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Rooms Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        @if ($rooms->isEmpty())
            <div class="text-center py-16 text-slate-400">
                <p class="text-lg">Kamar tidak ditemukan.</p>
                <p class="text-xs mt-1">Coba sesuaikan pencarian Anda atau tambahkan kamar baru.</p>
            </div>
        @else
            <!-- Bungkus tabel dengan div yang memiliki scrollbar horizontal khusus mobile -->
            <div class="w-full block overflow-x-auto">
                <table class="w-full divide-y divide-slate-100 text-xs sm:text-sm">
                    <thead>
                        <tr class="text-left font-semibold text-slate-400 bg-slate-50/50">
                            <th class="px-4 py-3 whitespace-nowrap">Nomor</th>
                            <th class="px-4 py-3 whitespace-nowrap">Tipe</th>
                            <th class="px-4 py-3 whitespace-nowrap">Harga</th>
                            <th class="px-4 py-3 whitespace-nowrap">Status</th>
                            <th class="px-4 py-3 whitespace-nowrap hidden md:table-cell">Deskripsi</th>
                            <th class="px-4 py-3 whitespace-nowrap text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-medium text-slate-700">
                        @foreach ($rooms as $room)
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-900 whitespace-nowrap">
                                    No. {{ $room->room_number }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $room->room_type }}
                                </td>
                                <td class="px-4 py-3 text-slate-900 font-bold whitespace-nowrap">
                                    {{ number_format($room->price_per_night / 1000, 0, ',', '.') }}k
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-semibold
                                        @if ($room->status === 'available') bg-emerald-50 text-emerald-700 @elseif ($room->status === 'occupied') bg-red-50 text-red-700 @else bg-amber-50 text-amber-700 @endif">
                                        {{ ucfirst($room->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-400 max-w-xs truncate hidden md:table-cell">
                                    {{ $room->description ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-right space-x-1 text-[10px] sm:text-xs font-bold whitespace-nowrap">
                                    <a href="{{ route('rooms.edit', $room->id) }}" class="inline-flex items-center px-2 py-1 text-[10px] sm:text-xs font-bold text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Edit</a>
                                    <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 text-[10px] sm:text-xs font-bold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-50">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
