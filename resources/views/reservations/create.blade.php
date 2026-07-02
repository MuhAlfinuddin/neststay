@extends('layouts.app')

@section('header_title', 'Buat Reservasi')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('reservations.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">← Kembali ke Daftar Reservasi</a>
        <h1 class="text-2xl font-black text-slate-900 mt-2">Buat Reservasi Baru</h1>
        <p class="text-xs text-slate-500">Daftarkan reservasi kamar baru. Sistem akan secara otomatis menghitung total biaya menginap.</p>
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

        <form action="{{ route('reservations.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <!-- Guest Selection -->
                <div>
                    <div class="flex justify-between items-center">
                        <label for="guest_id" class="block text-sm font-semibold text-slate-700">Pilih Tamu</label>
                        <a href="{{ route('guests.create') }}" class="text-xs text-indigo-600 font-semibold hover:underline">+ Registrasi Tamu Baru</a>
                    </div>
                    <select id="guest_id" name="guest_id" required class="mt-1 block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">-- Pilih Tamu --</option>
                        @foreach ($guests as $guest)
                            <option value="{{ $guest->id }}" {{ old('guest_id') == $guest->id ? 'selected' : '' }}>
                                {{ $guest->name }} (KTP: {{ $guest->identity_number }} - Tlp: {{ $guest->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Room Selection -->
                <div>
                    <div class="flex justify-between items-center">
                        <label for="room_id" class="block text-sm font-semibold text-slate-700">Pilih Kamar</label>
                        <span class="text-xs text-slate-400">Kamar dalam perbaikan disembunyikan</span>
                    </div>
                    <select id="room_id" name="room_id" required class="mt-1 block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">-- Pilih Kamar --</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                Kamar {{ $room->room_number }} (Tipe: {{ $room->room_type }} - Tarif: Rp {{ number_format($room->price_per_night, 0, ',', '.') }}/malam) - {{ ucfirst($room->status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="check_in" class="block text-sm font-semibold text-slate-700">Tanggal Check-in</label>
                        <input id="check_in" name="check_in" type="date" required min="{{ date('Y-m-d') }}" value="{{ old('check_in') }}" class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="check_out" class="block text-sm font-semibold text-slate-700">Tanggal Check-out</label>
                        <input id="check_out" name="check_out" type="date" required value="{{ old('check_out') }}" class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <!-- Initial Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700">Status Awal Pemesanan</label>
                    <select id="status" name="status" required class="mt-1 block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu Pembayaran/Konfirmasi)</option>
                        <option value="confirmed" {{ old('status') === 'confirmed' ? 'selected' : '' }}>Confirmed (Lunas / Down Payment)</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('reservations.index') }}" class="px-4 py-2 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md shadow-indigo-600/10">
                    Simpan Reservasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
