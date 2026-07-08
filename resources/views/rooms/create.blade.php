@extends('layouts.app')

@section('header_title', 'Tambah Kamar')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('rooms.index') }}" class="text-xs font-semibold text-[var(--color-teak-deep)] hover:underline">← Kembali ke Daftar Kamar</a>
        <h1 class="text-2xl font-black text-slate-900 mt-2">Tambah Kamar Baru</h1>
        <p class="text-xs text-slate-500">Isi detail di bawah untuk mendaftarkan kamar baru pada homestay Anda.</p>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-100">
                <div class="flex">
                    <div class="flex-shrink-0 text-red-500 font-bold">⚠️</div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pengisian:</h3>
                        <ul class="mt-2 list-disc list-inside text-xs text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('rooms.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="room_number" class="block text-sm font-semibold text-slate-700">Nomor / Nama Kamar</label>
                    <input id="room_number" name="room_number" type="text" placeholder="Contoh: 101, Deluxe-A" required value="{{ old('room_number') }}" class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">
                </div>

                <div>
                    <label for="room_type" class="block text-sm font-semibold text-slate-700">Tipe / Jenis Kamar</label>
                    <input id="room_type" name="room_type" type="text" placeholder="Contoh: Standard, Deluxe, Family" required value="{{ old('room_type') }}" class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="price_per_night" class="block text-sm font-semibold text-slate-700">Harga per Malam (Rp)</label>
                    <input id="price_per_night" name="price_per_night" type="number" min="0" placeholder="Contoh: 250000" required value="{{ old('price_per_night') }}" class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700">Status Awal</label>
                    <select id="status" name="status" required class="mt-1 block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">
                        <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Tersedia (Available)</option>
                        <option value="occupied" {{ old('status') === 'occupied' ? 'selected' : '' }}>Terisi (Occupied)</option>
                        <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Perbaikan (Maintenance)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700">Deskripsi Kamar</label>
                <textarea id="description" name="description" rows="3" placeholder="Tambahkan deskripsi fasilitas kamar, e.g. AC, TV, King Bed..." class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('rooms.index') }}" class="px-4 py-2 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition shadow-md shadow-indigo-600/10">
                    Simpan Kamar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
