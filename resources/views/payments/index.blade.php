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
        <button type="button" onclick="openPaymentModal()" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition shadow-md shadow-indigo-600/10">
            + Catat Pembayaran Baru
        </button>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
        <form action="{{ route('payments.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 sm:items-end">
            <div class="w-full sm:w-48">
                <label for="method" class="block text-xs font-semibold text-slate-400 uppercase mb-1">Metode Bayar</label>
                <select id="method" name="method" class="appearance-none block w-full px-3 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-xs">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('method') === 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                    <option value="transfer" {{ request('method') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="card" {{ request('method') === 'card' ? 'selected' : '' }}>Kartu Kredit/Debit</option>
                    <option value="qris" {{ request('method') === 'qris' ? 'selected' : '' }}>QRIS (Demo)</option>
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

    <!-- Payments Table / Card List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        @if ($payments->isEmpty())
            <div class="text-center py-16 text-slate-400">
                <p class="text-lg">Transaksi belum tercatat.</p>
                <p class="text-xs mt-1">Gunakan tombol di atas untuk mencatat pembayaran pertama.</p>
            </div>
        @else
            <!-- Desktop Table -->
            <div class="hidden md:block w-full overflow-x-auto">
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
                                <td class="px-6 py-4">{{ $payment->reservation->guest->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-xs">
                                        Kamar {{ $payment->reservation->room->room_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $payment->payment_date->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 font-bold text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 capitalize text-slate-500">{{ $payment->payment_method }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if ($payment->payment_status === 'paid') bg-emerald-50 text-emerald-700 @elseif ($payment->payment_status === 'down_payment') bg-amber-50 text-amber-700 @else bg-red-50 text-red-700 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('payments.show', $payment->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 min-h-[36px] text-xs font-bold text-indigo-600 hover:text-indigo-900 border border-indigo-100 hover:bg-indigo-50 rounded-lg transition">
                                        Nota
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2 text-xs font-bold">
                                    <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan transaksi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 min-h-[36px] text-xs font-bold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="block md:hidden space-y-4 p-4">
                @foreach ($payments as $payment)
                    @php
                        $methodIcons = ['cash' => '💵', 'transfer' => '🏦', 'card' => '💳', 'qris' => '📱'];
                        $methodIcon = $methodIcons[$payment->payment_method] ?? '💳';
                        $statusStyles = [
                            'paid' => ['border' => 'border-emerald-300', 'bg' => 'bg-emerald-50', 'badge' => 'bg-emerald-100 text-emerald-700', 'icon' => '✅'],
                            'down_payment' => ['border' => 'border-amber-300', 'bg' => 'bg-amber-50', 'badge' => 'bg-amber-100 text-amber-700', 'icon' => '💸'],
                        ];
                        $s = $statusStyles[$payment->payment_status] ?? ['border' => 'border-red-300', 'bg' => 'bg-red-50', 'badge' => 'bg-red-100 text-red-700', 'icon' => '❌'];
                    @endphp
                    <div class="rounded-2xl border-2 {{ $s['border'] }} {{ $s['bg'] }} shadow-sm overflow-hidden">
                        <div class="p-4 space-y-3">
                            <!-- Header: Guest + Status -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 truncate">{{ $payment->reservation->guest->name }}</h4>
                                    <p class="text-xs text-slate-400">Kamar {{ $payment->reservation->room->room_number }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold shrink-0 whitespace-nowrap {{ $s['badge'] }}">
                                    {{ $s['icon'] }} {{ ucfirst(str_replace('_', ' ', $payment->payment_status)) }}
                                </span>
                            </div>

                            <!-- Amount -->
                            <div class="text-center py-1">
                                <span class="text-2xl font-black text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>

                            <!-- Method + Date -->
                            <div class="flex items-center justify-center gap-4 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1.5">{{ $methodIcon }} <span class="capitalize">{{ $payment->payment_method }}</span></span>
                                <span class="inline-flex items-center gap-1.5">📅 {{ $payment->payment_date->format('d M Y H:i') }}</span>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3">
                                <a href="{{ route('payments.show', $payment->id) }}" target="_blank"
                                   class="flex-1 flex items-center justify-center px-4 py-3 min-h-[44px] text-sm font-bold text-indigo-600 border-2 border-indigo-200 hover:bg-indigo-50 rounded-xl transition">
                                    📄 Nota
                                </a>
                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan transaksi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full flex items-center justify-center px-4 py-3 min-h-[44px] text-sm font-bold text-red-700 bg-red-50 hover:bg-red-100 rounded-xl transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-50">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Pilih Metode Pembayaran -->
<div id="paymentModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closePaymentModal()"></div>
    <div class="flex items-center justify-center min-h-full p-4">
        <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-100 p-6 sm:p-8">
            <button onclick="closePaymentModal()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 transition" aria-label="Tutup">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="text-center mb-6">
                <h2 class="text-xl font-black text-slate-900">Pilih Metode Pembayaran</h2>
                <p class="text-sm text-slate-500 mt-1">Pilih metode yang sesuai untuk mencatat transaksi</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Tunai -->
                <a href="{{ route('payments.create', ['method' => 'cash']) }}"
                   class="flex flex-col items-center gap-3 p-5 rounded-xl border-2 border-transparent bg-emerald-50 hover:bg-emerald-100 hover:border-emerald-300 transition group">
                    <span class="text-4xl">💵</span>
                    <div class="text-center">
                        <p class="font-bold text-slate-900">Tunai</p>
                        <p class="text-xs text-slate-500">Cash</p>
                    </div>
                </a>

                <!-- Transfer -->
                <a href="{{ route('payments.create', ['method' => 'transfer']) }}"
                   class="flex flex-col items-center gap-3 p-5 rounded-xl border-2 border-transparent bg-blue-50 hover:bg-blue-100 hover:border-blue-300 transition group">
                    <span class="text-4xl">🏦</span>
                    <div class="text-center">
                        <p class="font-bold text-slate-900">Transfer</p>
                        <p class="text-xs text-slate-500">Bank</p>
                    </div>
                </a>

                <!-- Kartu -->
                <a href="{{ route('payments.create', ['method' => 'card']) }}"
                   class="flex flex-col items-center gap-3 p-5 rounded-xl border-2 border-transparent bg-purple-50 hover:bg-purple-100 hover:border-purple-300 transition group">
                    <span class="text-4xl">💳</span>
                    <div class="text-center">
                        <p class="font-bold text-slate-900">Kartu</p>
                        <p class="text-xs text-slate-500">Kredit/Debit</p>
                    </div>
                </a>

                <!-- QRIS -->
                <a href="{{ route('payments.create', ['method' => 'qris']) }}"
                   class="flex flex-col items-center gap-3 p-5 rounded-xl border-2 border-transparent bg-gradient-to-br from-cyan-50 to-green-50 hover:from-cyan-100 hover:to-green-100 hover:border-emerald-400 transition group relative overflow-hidden">
                    <span class="text-4xl relative z-10">📱</span>
                    <div class="text-center relative z-10">
                        <p class="font-bold text-slate-900">QRIS</p>
                        <p class="text-xs text-slate-500">Demo</p>
                    </div>
                    <span class="absolute top-2 right-2 bg-emerald-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded z-10">DEMO</span>
                </a>
            </div>

            <div class="mt-6 text-center">
                <button onclick="closePaymentModal()" class="text-sm font-semibold text-slate-400 hover:text-slate-600 transition">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openPaymentModal() {
        document.getElementById('paymentModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closePaymentModal();
    });
</script>
@endsection
