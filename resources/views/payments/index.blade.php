@extends('layouts.app')

@section('header_title', 'Catatan Transaksi Keuangan')

@section('content')
<div class="space-y-6">
    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Daftar Transaksi Pembayaran</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau dan verifikasi pembayaran tamu homestay Anda.</p>
        </div>
        <a href="{{ route('payments.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition shadow-md shadow-indigo-600/10">
            + Catat Pembayaran Baru
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
        <form action="{{ route('payments.index') }}" method="GET" class="flex gap-4 items-end">
            <div class="w-full sm:w-48">
                <label for="method" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Metode Bayar</label>
                <select id="method" name="method" class="appearance-none block w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-xs">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('method') === 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                    <option value="transfer" {{ request('method') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="card" {{ request('method') === 'card' ? 'selected' : '' }}>Kartu Kredit/Debit</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition">
                    Filter
                </button>
                <a href="{{ route('payments.index') }}" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl text-center transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if ($payments->isEmpty())
            <div class="text-center py-16 text-slate-400">
                <p class="text-lg">Transaksi belum tercatat.</p>
                <p class="text-xs mt-1">Gunakan tombol di atas untuk mencatat pembayaran pertama.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead>
                        <tr class="text-left font-semibold text-slate-400 bg-slate-50/50">
                            <th class="px-6 py-4">Tamu</th>
                            <th class="px-6 py-4">Kamar</th>
                            <th class="px-6 py-4">Tanggal Pembayaran</th>
                            <th class="px-6 py-4">Jumlah Bayar</th>
                            <th class="px-6 py-4">Metode</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Nota / Resi</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-medium text-slate-700">
                        @foreach ($payments as $payment)
                            <tr>
                                <td class="px-6 py-4">
                                    {{ $payment->reservation->guest->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-xs">
                                        Kamar {{ $payment->reservation->room->room_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $payment->payment_date->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 capitalize text-slate-500">
                                    {{ $payment->payment_method }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if ($payment->payment_status === 'paid') bg-emerald-50 text-emerald-700 @elseif ($payment->payment_status === 'down_payment') bg-amber-50 text-amber-700 @else bg-red-50 text-red-700 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('payments.show', $payment->id) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-900 border border-indigo-100 hover:bg-indigo-50 px-2 py-1 rounded transition">
                                        📄 Buka Nota
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2 text-xs font-bold">
                                    <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan transaksi ini?');">
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
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
