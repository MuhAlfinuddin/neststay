@extends('layouts.app')

@section('header_title', 'Catat Pembayaran')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('payments.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">← Kembali ke Daftar Transaksi</a>
        <h1 class="text-2xl font-black text-slate-900 mt-2">Catat Pembayaran Baru</h1>
        <p class="text-xs text-slate-500">Merekam pembayaran masuk dari tamu untuk reservasi tertentu.</p>
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

        <form action="{{ route('payments.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <!-- Reservation Selection -->
                <div>
                    <label for="reservation_id" class="block text-sm font-semibold text-slate-700">Pilih Reservasi</label>
                    <select id="reservation_id" name="reservation_id" required class="mt-1 block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">-- Pilih Booking/Reservasi --</option>
                        @foreach ($reservations as $res)
                            <option value="{{ $res->id }}" {{ old('reservation_id', $selectedReservationId) == $res->id ? 'selected' : '' }}>
                                Tamu: {{ $res->guest->name }} - Kamar {{ $res->room->room_number }} (Tarif: Rp {{ number_format($res->total_price, 0, ',', '.') }}) - Status Booking: {{ ucfirst($res->status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-semibold text-slate-700">Jumlah Pembayaran (Rp)</label>
                        <input id="amount" name="amount" type="number" min="0" required placeholder="Contoh: 500000" value="{{ old('amount') }}" class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <label for="payment_date" class="block text-sm font-semibold text-slate-700">Tanggal & Waktu Bayar</label>
                        <input id="payment_date" name="payment_date" type="datetime-local" required value="{{ old('payment_date', now()->format('Y-m-d\TH:i')) }}" class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-semibold text-slate-700">Metode Pembayaran</label>
                        <select id="payment_method" name="payment_method" required class="mt-1 block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                            <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Kartu Kredit/Debit</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="payment_status" class="block text-sm font-semibold text-slate-700">Status Transaksi</label>
                        <select id="payment_status" name="payment_status" required class="mt-1 block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                            <option value="down_payment" {{ old('payment_status') === 'down_payment' ? 'selected' : '' }}>Down Payment (DP)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('payments.index') }}" class="px-4 py-2 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md shadow-indigo-600/10">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
