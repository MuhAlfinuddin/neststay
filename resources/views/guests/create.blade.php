@extends('layouts.app')

@section('header_title', 'Registrasi Tamu')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('guests.index') }}" class="text-xs font-semibold text-[var(--color-teak-deep)] hover:underline">← Kembali ke Daftar Tamu</a>
        <h1 class="text-2xl font-black text-slate-900 mt-2">Registrasi Tamu Baru</h1>
        <p class="text-xs text-slate-500">Mendaftarkan data identitas tamu baru untuk mempermudah pemesanan kamar.</p>
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

        <form action="{{ route('guests.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700">Nama Lengkap Tamu</label>
                    <input id="name" name="name" type="text" required placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="identity_number" class="block text-sm font-semibold text-slate-700">Nomor Identitas (NIK/KTP/Paspor)</label>
                        <input id="identity_number" name="identity_number" type="text" required placeholder="Contoh: 327102XXXXXXXXXX" value="{{ old('identity_number') }}" class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700">Nomor Telepon</label>
                        <input id="phone" name="phone" type="text" required placeholder="Contoh: 0812XXXXXXXX" value="{{ old('phone') }}" class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">Alamat Email (Opsional)</label>
                    <input id="email" name="email" type="email" placeholder="Contoh: budi@gmail.com" value="{{ old('email') }}" class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('guests.index') }}" class="px-4 py-2 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition shadow-md shadow-indigo-600/10">
                    Simpan Tamu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
