@extends('layouts.app')

@section('header_title', 'Data Tamu')

@section('content')
<div class="space-y-6">
    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Daftar Tamu</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola data riwayat tamu yang pernah menginap.</p>
        </div>
        <a href="{{ route('guests.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition shadow-md shadow-indigo-600/10">
            + Registrasi Tamu Baru
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
        <form action="{{ route('guests.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 sm:items-end">
            <div class="flex-grow">
                <label for="search" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Cari Tamu</label>
                <input id="search" name="search" type="text" placeholder="Cari nama, email, telepon, atau nomor identitas (KTP)..." value="{{ request('search') }}" class="appearance-none block w-full px-3 py-2 border border-slate-200 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-xs">
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition">
                    Cari
                </button>
                <a href="{{ route('guests.index') }}" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl text-center transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Guests Table / Card List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        @if ($guests->isEmpty())
            <div class="text-center py-16 text-slate-400">
                <p class="text-lg">Tamu tidak ditemukan.</p>
                <p class="text-xs mt-1">Coba sesuaikan pencarian Anda atau daftarkan tamu baru.</p>
            </div>
        @else
            <!-- Desktop Table -->
            <div class="hidden md:block w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead>
                        <tr class="text-left font-semibold text-slate-400 bg-slate-50/50">
                            <th class="px-6 py-4">Nama Tamu</th>
                            <th class="px-6 py-4">Nomor Identitas (KTP)</th>
                            <th class="px-6 py-4">Nomor Telepon</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-medium text-slate-700">
                        @foreach ($guests as $guest)
                            <tr>
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $guest->name }}</td>
                                <td class="px-6 py-4">{{ $guest->identity_number }}</td>
                                <td class="px-6 py-4">{{ $guest->phone }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $guest->email ?? '-' }}</td>
                                <td class="px-6 py-4 text-right space-x-2 text-xs font-bold">
                                    <a href="{{ route('guests.edit', $guest->id) }}" class="inline-flex items-center px-3 py-1.5 min-h-[36px] font-bold text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Edit</a>
                                    <form action="{{ route('guests.destroy', $guest->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tamu ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 min-h-[36px] font-bold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="block md:hidden divide-y divide-slate-50">
                @foreach ($guests as $guest)
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[var(--color-teak-deep)]/10 text-[var(--color-teak-deep)] font-extrabold flex items-center justify-center shrink-0 text-sm">
                            {{ strtoupper(mb_substr($guest->name, 0, 2)) }}
                        </div>
                        <div class="flex-grow min-w-0">
                            <h4 class="font-bold text-slate-900">{{ $guest->name }}</h4>
                            <p class="text-xs text-slate-500 truncate">{{ $guest->identity_number }} · {{ $guest->phone }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $guest->email ?? '-' }}</p>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <a href="{{ route('guests.edit', $guest->id) }}" class="inline-flex items-center justify-center px-3 min-h-[36px] text-xs font-bold text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Edit</a>
                            <form action="{{ route('guests.destroy', $guest->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tamu ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center px-3 min-h-[36px] text-xs font-bold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-50">
                {{ $guests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
