@extends('layouts.guest')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden bg-white py-24 sm:py-32">
    <!-- Background Gradients -->
    <div class="absolute inset-y-0 right-1/2 -z-10 -mr-96 w-[200%] origin-top-right skew-x-[-30deg] bg-slate-50 shadow-xl shadow-indigo-600/10 ring-1 ring-indigo-50 sm:-mr-80 lg:-mr-96" aria-hidden="true"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            <div class="lg:col-span-7">
                <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-semibold text-indigo-600 mb-6">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Platform SaaS Homestay Terbaik #1 di Sleman</span>
                </div>
                <h1 class="text-4xl sm:text-6xl font-black text-slate-900 tracking-tight leading-none">
                    Kelola Homestay Lebih Praktis & Bebas <span class="text-indigo-600 bg-clip-text">Overbooking</span>
                </h1>
                <p class="mt-6 text-lg text-slate-600 leading-relaxed max-w-2xl">
                    StayNest membantu pemilik homestay, guest house, dan villa mengelola kamar, tamu, pemesanan, pembayaran, dan laporan keuangan dalam satu sistem multi-tenant terpusat.
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-lg shadow-indigo-600/20">
                        Mulai Coba Gratis 14 Hari
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center px-6 py-3 text-base font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Lihat Fitur Lengkap
                    </a>
                </div>
                <div class="mt-12 grid grid-cols-3 gap-6 border-t border-slate-100 pt-8 max-w-lg">
                    <div>
                        <p class="text-3xl font-extrabold text-slate-900">100%</p>
                        <p class="text-sm text-slate-500 mt-1">Multi-Tenant Scoped</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-slate-900">Real-Time</p>
                        <p class="text-sm text-slate-500 mt-1">Pencegahan Overbooking</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-slate-900">Instant</p>
                        <p class="text-sm text-slate-500 mt-1">Laporan Keuangan</p>
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-5 relative">
                <!-- Beautiful visual card overlay representation -->
                <div class="relative bg-gradient-to-tr from-indigo-600 to-violet-500 rounded-3xl p-8 text-white shadow-2xl overflow-hidden aspect-[4/3] flex flex-col justify-between">
                    <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute -left-16 -bottom-16 w-48 h-48 bg-emerald-500/20 rounded-full blur-2xl"></div>
                    
                    <div class="relative flex justify-between items-center">
                        <div class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-semibold">StayNest Pro</div>
                        <div class="text-xl font-bold tracking-tight">StayNest.com</div>
                    </div>
                    
                    <div class="relative my-6">
                        <p class="text-sm text-indigo-100 uppercase tracking-wider">Okupansi Kamar Hari Ini</p>
                        <h2 class="text-5xl font-black mt-2">87.5%</h2>
                        <p class="text-xs text-indigo-100 mt-2">7 dari 8 Kamar Terisi</p>
                    </div>
                    
                    <div class="relative flex items-center justify-between border-t border-white/20 pt-4">
                        <div>
                            <p class="text-xs text-indigo-100">Pendapatan Bulan Ini</p>
                            <p class="text-lg font-bold">Rp 12.850.000</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-400/20 text-emerald-300">
                            +12.4% vs bln lalu
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div id="features" class="py-24 bg-slate-50 border-y border-slate-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-base font-semibold text-indigo-600 uppercase tracking-wide">Fitur Unggulan</h2>
            <p class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-900">
                Solusi All-in-One Pengelolaan Homestay Modern
            </p>
            <p class="mt-4 text-slate-600">
                Lupakan pencatatan manual di kertas yang rawan hilang dan membingungkan. Kelola semua operasi dalam satu dasbor responsif.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Room Management -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-6 font-bold text-xl">🛏️</div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Manajemen Kamar</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Kelola status kamar secara real-time (Tersedia, Terisi, Perawatan). Atur harga per malam dan jenis kamar dengan fleksibel.
                </p>
            </div>

            <!-- Booking & Overbooking Prevention -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-6 font-bold text-xl">📅</div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Sistem Reservasi & Validasi</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Sistem otomatis memeriksa ketersediaan kamar pada tanggal yang dipilih untuk mencegah terjadinya pemesanan ganda (overbooking).
                </p>
            </div>

            <!-- Multi-Tenant Role Access -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-6 font-bold text-xl">👥</div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Multi-Tenant & Hak Akses</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Setiap homestay terisolasi aman. Pemilik (Owner) dapat mengundang Staff dengan hak akses terbatas sesuai tugas operasional.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Pricing Section -->
<div id="pricing" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-base font-semibold text-indigo-600 uppercase tracking-wide">Pilihan Harga</h2>
            <p class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-900">
                Pilih Paket yang Sesuai Kebutuhan Homestay Anda
            </p>
            <p class="mt-4 text-slate-600">
                Semua paket dilengkapi dengan fitur dasar manajemen kamar, reservasi, pembayaran, dan laporan bulanan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Free Plan -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 flex flex-col justify-between hover:shadow-lg transition">
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">Paket Basic</h3>
                    <p class="mt-2 text-sm text-slate-500">Cocok untuk homestay kecil yang baru memulai digitalisasi.</p>
                    <p class="mt-6 flex items-baseline">
                        <span class="text-5xl font-black tracking-tight text-slate-900">Rp 199K</span>
                        <span class="ml-1 text-sm font-semibold text-slate-500">/ bulan</span>
                    </p>
                    <ul class="mt-8 space-y-4 text-sm text-slate-600">
                        <li class="flex items-center space-x-3">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Maksimal 10 Kamar</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Akses untuk 2 Staff</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Manajemen Tamu & Pembayaran</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Pencegahan Overbooking</span>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="mt-8 block w-full text-center py-3 px-4 rounded-xl border border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-bold transition">
                    Mulai Sekarang
                </a>
            </div>

            <!-- Pro Plan -->
            <div class="bg-white p-8 rounded-3xl border-2 border-indigo-600 flex flex-col justify-between hover:shadow-lg transition relative">
                <div class="absolute top-0 right-8 -translate-y-1/2 px-3 py-1 bg-indigo-600 text-white text-xs font-bold rounded-full uppercase tracking-wider">
                    Terpopuler
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">Paket Premium</h3>
                    <p class="mt-2 text-sm text-slate-500">Sempurna untuk homestay skala menengah ke atas dengan banyak cabang.</p>
                    <p class="mt-6 flex items-baseline">
                        <span class="text-5xl font-black tracking-tight text-slate-900">Rp 499K</span>
                        <span class="ml-1 text-sm font-semibold text-slate-500">/ bulan</span>
                    </p>
                    <ul class="mt-8 space-y-4 text-sm text-slate-600">
                        <li class="flex items-center space-x-3">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Kamar Tidak Terbatas</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Staff Tidak Terbatas</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Laporan Keuangan & Grafik Analisis</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Invoice Kustom & Pencetakan Struk</span>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="mt-8 block w-full text-center py-3 px-4 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 font-bold transition shadow-md shadow-indigo-600/20">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
