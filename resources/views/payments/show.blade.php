<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran #SN-{{ $payment->id }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            padding: 2rem 1rem;
        }
        .invoice-card {
            background-color: #ffffff;
            max-width: 800px;
            margin: 0 auto;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            padding: 2.5rem;
        }
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .invoice-card {
                border: none;
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="invoice-card">
        <!-- Print Header Action -->
        <div class="no-print flex justify-between items-center mb-8 pb-6 border-b border-slate-100">
            <a href="javascript:window.history.back();" class="text-sm font-semibold text-indigo-600 hover:underline">← Kembali</a>
            <button onclick="window.print();" class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow">
                🖨️ Cetak Nota (Print)
            </button>
        </div>

        <!-- Invoice Header -->
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-black text-indigo-600">Stay<span class="text-emerald-500">Nest</span></h1>
                <p class="text-xs text-slate-400 font-semibold uppercase mt-1">Platform Manajemen Homestay SaaS</p>
                <div class="mt-4 text-sm text-slate-500">
                    <p class="font-bold text-slate-800">{{ $payment->homestay->name }}</p>
                    <p>{{ $payment->homestay->address }}</p>
                    <p>Telp: {{ $payment->homestay->phone }}</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-slate-800">NOTA PEMBAYARAN</h2>
                <p class="text-sm text-slate-500 mt-1">No: #SN-{{ $payment->id }}</p>
                <p class="text-sm text-slate-500">Tanggal: {{ $payment->payment_date->format('d M Y H:i') }}</p>
                <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase">
                    {{ $payment->payment_status === 'paid' ? 'LUNAS (PAID)' : 'DOWN PAYMENT (DP)' }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mt-12 pb-8 border-b border-slate-100">
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Ditujukan Kepada (Tamu):</p>
                <p class="text-base font-bold text-slate-800 mt-2">{{ $payment->reservation->guest->name }}</p>
                <p class="text-sm text-slate-500 mt-1">No. Identitas: {{ $payment->reservation->guest->identity_number }}</p>
                <p class="text-sm text-slate-500">No. Telepon: {{ $payment->reservation->guest->phone }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Rincian Reservasi:</p>
                <p class="text-sm text-slate-700 mt-2"><span class="font-bold">Kamar:</span> No. {{ $payment->reservation->room->room_number }} ({{ $payment->reservation->room->room_type }})</p>
                <p class="text-sm text-slate-700"><span class="font-bold">Periode:</span> {{ $payment->reservation->check_in->format('d M Y') }} s/d {{ $payment->reservation->check_out->format('d M Y') }}</p>
                <p class="text-sm text-slate-700"><span class="font-bold">Total Menginap:</span> {{ max(1, $payment->reservation->check_in->diffInDays($payment->reservation->check_out)) }} malam</p>
            </div>
        </div>

        <!-- Table Billing Details -->
        <table class="min-w-full divide-y divide-slate-100 text-sm mt-8">
            <thead>
                <tr class="text-left font-bold text-slate-400 bg-slate-50">
                    <th class="px-4 py-3">Deskripsi Layanan</th>
                    <th class="px-4 py-3 text-right">Harga Satuan</th>
                    <th class="px-4 py-3 text-right">Durasi</th>
                    <th class="px-4 py-3 text-right">Total Tarif</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 font-medium text-slate-700">
                <tr>
                    <td class="px-4 py-4">
                        Sewa Kamar No. {{ $payment->reservation->room->room_number }} ({{ $payment->reservation->room->room_type }})
                    </td>
                    <td class="px-4 py-4 text-right">
                        Rp {{ number_format($payment->reservation->room->price_per_night, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-4 text-right">
                        {{ max(1, $payment->reservation->check_in->diffInDays($payment->reservation->check_out)) }} malam
                    </td>
                    <td class="px-4 py-4 text-right font-bold text-slate-900">
                        Rp {{ number_format($payment->reservation->total_price, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Summary Totals -->
        <div class="mt-8 border-t border-slate-100 pt-6 flex justify-end">
            <div class="w-64 space-y-3 text-sm">
                <div class="flex justify-between font-medium text-slate-500">
                    <span>Total Tagihan:</span>
                    <span>Rp {{ number_format($payment->reservation->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-slate-900 border-t border-slate-100 pt-3 text-base">
                    <span>Jumlah Dibayar:</span>
                    <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs font-semibold text-slate-400 uppercase pt-2">
                    <span>Metode Bayar:</span>
                    <span>{{ $payment->payment_method }}</span>
                </div>
            </div>
        </div>

        <!-- Receipt Footer Note -->
        <div class="mt-16 text-center text-xs text-slate-400 border-t border-slate-100 pt-8">
            <p>Terima kasih atas kunjungan Anda di {{ $payment->homestay->name }}.</p>
            <p class="mt-1">Nota pembayaran ini diterbitkan secara sah melalui sistem StayNest SaaS.</p>
        </div>
    </div>
</body>
</html>
