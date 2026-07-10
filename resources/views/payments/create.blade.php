@extends('layouts.app')

@section('header_title', 'Catat Pembayaran')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('payments.index') }}" class="text-xs font-semibold text-[var(--color-teak-deep)] hover:underline">← Kembali ke Daftar Transaksi</a>
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
                    <select id="reservation_id" name="reservation_id" required class="mt-1 block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">
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
                        <input id="amount" name="amount" type="number" min="0" required placeholder="Contoh: 500000" value="{{ old('amount') }}" inputmode="numeric" autocomplete="off" class="mt-1 appearance-none block w-full px-4 py-3 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] text-[16px]">
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <label for="payment_date" class="block text-sm font-semibold text-slate-700">Tanggal & Waktu Bayar</label>
                        <input id="payment_date" name="payment_date" type="datetime-local" required value="{{ old('payment_date', now()->format('Y-m-d\TH:i')) }}" class="mt-1 appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-semibold text-slate-700">Metode Pembayaran</label>
                        <select id="payment_method" name="payment_method" required onchange="toggleQrisPanel()" class="mt-1 block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                            <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Kartu Kredit/Debit</option>
                            <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>QRIS (Demo)</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="payment_status" class="block text-sm font-semibold text-slate-700">Status Transaksi</label>
                        <select id="payment_status" name="payment_status" required class="mt-1 block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[var(--color-teak)] focus:border-[var(--color-teak)] sm:text-sm">
                            <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                            <option value="down_payment" {{ old('payment_status') === 'down_payment' ? 'selected' : '' }}>Down Payment (DP)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('payments.index') }}" class="flex items-center justify-center px-6 py-3 min-h-[44px] text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition w-full sm:w-auto">
                    Batal
                </a>
                <button type="submit" class="flex items-center justify-center px-6 py-3 min-h-[44px] text-sm font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition shadow-md w-full sm:w-auto">
                    Simpan Transaksi
                </button>
            </div>
        </form>

        <!-- QRIS Demo Panel -->
        <div id="qrisPanel" class="hidden mt-6 p-6 rounded-2xl bg-gradient-to-br from-cyan-50 via-white to-emerald-50 border border-emerald-200 shadow-sm">
            <div class="text-center space-y-5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                    📱 QRIS Simulation
                </div>

                <h3 class="text-lg font-black text-slate-900">Scan QRIS untuk Membayar</h3>

                <!-- Demo QR Code -->
                <div class="inline-flex items-center justify-center p-4 bg-white rounded-2xl shadow-md border border-slate-100">
                    <svg width="180" height="180" viewBox="0 0 33 33" class="block">
                        <rect width="33" height="33" fill="white"/>
                        <rect x="0" y="0" width="7" height="7" fill="black" rx="1"/>
                        <rect x="26" y="0" width="7" height="7" fill="black" rx="1"/>
                        <rect x="0" y="26" width="7" height="7" fill="black" rx="1"/>
                        <rect x="13" y="13" width="7" height="7" fill="black" rx="1"/>
                        <rect x="26" y="13" width="2" height="2" fill="black"/>
                        <rect x="0" y="13" width="2" height="2" fill="black"/>
                        <rect x="13" y="0" width="2" height="2" fill="black"/>
                        <rect x="13" y="26" width="2" height="2" fill="black"/>
                        <rect x="4" y="10" width="2" height="2" fill="black"/>
                        <rect x="10" y="4" width="2" height="2" fill="black"/>
                        <rect x="20" y="4" width="2" height="2" fill="black"/>
                        <rect x="26" y="10" width="2" height="2" fill="black"/>
                        <rect x="4" y="22" width="2" height="2" fill="black"/>
                        <rect x="22" y="22" width="2" height="2" fill="black"/>
                        <rect x="18" y="10" width="2" height="2" fill="black"/>
                        <rect x="10" y="18" width="2" height="2" fill="black"/>
                        <rect x="6" y="14" width="2" height="4" fill="black"/>
                        <rect x="20" y="14" width="2" height="4" fill="black"/>
                        <rect x="14" y="6" width="4" height="2" fill="black"/>
                        <rect x="14" y="20" width="4" height="2" fill="black"/>
                        <rect x="2" y="2" width="3" height="3" fill="white"/>
                        <rect x="28" y="2" width="3" height="3" fill="white"/>
                        <rect x="2" y="28" width="3" height="3" fill="white"/>
                        <rect x="14" y="14" width="5" height="5" fill="white"/>
                        <circle cx="16.5" cy="16.5" r="1.5" fill="white"/>
                    </svg>
                </div>

                <div class="text-sm text-slate-600 space-y-1">
                    <p class="font-semibold">Total Pembayaran: <span id="qrisAmount" class="text-lg font-black text-emerald-700">Rp 0</span></p>
                    <p>Scan menggunakan aplikasi e-wallet (GoPay, OVO, DANA, dll)</p>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="button" id="qrisConfirmBtn"
                            onclick="confirmQrisPayment()"
                            class="w-full py-4 min-h-[52px] bg-emerald-600 hover:bg-emerald-700 text-white font-black text-base rounded-xl transition shadow-lg flex items-center justify-center gap-3 disabled:opacity-60 disabled:cursor-not-allowed">
                        <span id="qrisBtnText">Konfirmasi Pembayaran</span>
                        <svg id="qrisBtnSpinner" class="hidden h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </button>
                    <p class="text-xs text-slate-400">* Demo — tidak ada transaksi sungguhan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-select payment method from URL param
    document.addEventListener('DOMContentLoaded', function() {
        var params = new URLSearchParams(window.location.search);
        var method = params.get('method');
        if (method) {
            var select = document.getElementById('payment_method');
            if ([...select.options].some(function(o) { return o.value === method; })) {
                select.value = method;
                toggleQrisPanel();
            }
        }
    });

    function toggleQrisPanel() {
        var panel = document.getElementById('qrisPanel');
        var method = document.getElementById('payment_method').value;
        panel.classList.toggle('hidden', method !== 'qris');
    }

    function confirmQrisPayment() {
        var btn = document.getElementById('qrisConfirmBtn');
        var text = document.getElementById('qrisBtnText');
        var spinner = document.getElementById('qrisBtnSpinner');
        var amountInput = document.getElementById('amount');
        var amount = parseInt(amountInput.value) || 0;

        if (amount <= 0) {
            amountInput.focus();
            amountInput.classList.add('ring-2', 'ring-red-400');
            setTimeout(function() { amountInput.classList.remove('ring-2', 'ring-red-400'); }, 2000);
            return;
        }

        btn.disabled = true;
        text.textContent = 'Memproses Pembayaran...';
        spinner.classList.remove('hidden');

        setTimeout(function() {
            spinner.classList.add('hidden');
            text.textContent = '✓ Pembayaran Berhasil!';
            btn.className = 'w-full py-4 min-h-[52px] bg-emerald-600 text-white font-black text-base rounded-xl shadow-lg flex items-center justify-center gap-3 cursor-default';

            // Set payment_status to paid and submit
            document.getElementById('payment_status').value = 'paid';
            document.querySelector('form').submit();
        }, 3000);
    }

    // Update QRIS amount display when amount input changes
    document.addEventListener('DOMContentLoaded', function() {
        var amountInput = document.getElementById('amount');
        var qrisAmount = document.getElementById('qrisAmount');
        if (amountInput && qrisAmount) {
            amountInput.addEventListener('input', function() {
                var val = parseInt(this.value) || 0;
                qrisAmount.textContent = 'Rp ' + val.toLocaleString('id-ID');
            });
        }
    });
</script>
@endsection
