<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'StayNest - Dashboard' }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--color-paper);
            color: var(--color-ink);
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Zilla Slab', serif;
            color: var(--color-teak-deep);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[var(--color-paper)] text-[var(--color-ink)] selection:bg-[var(--color-marigold)] selection:text-[var(--color-ink)] min-h-screen flex">

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-20 w-64 bg-[var(--color-teak-deep)] text-[#F6EFDC] flex flex-col justify-between border-r border-[var(--color-teak)]">
        <div>
            <!-- Header Brand -->
            <div class="h-16 flex items-center px-6 border-b border-[var(--color-teak)]">
                <a href="#" class="flex items-center space-x-2">
                    <span class="text-2xl font-black tracking-tight text-[var(--color-marigold)]">Stay<span class="text-[var(--color-leaf)]">Nest</span></span>
                </a>
            </div>

            <!-- Profile Info Context -->
            <div class="px-6 py-4 border-b border-[var(--color-teak)] bg-[var(--color-teak)]/40">
                <p class="text-xs text-[var(--color-paper-deep)] font-semibold uppercase tracking-wider">Tenant / Homestay</p>
                <p class="text-sm font-bold truncate mt-1 text-[#F6EFDC]">
                    {{ auth()->user()->role === 'super_admin' ? 'Platform Administrator' : optional(auth()->user()->homestay)->name ?? 'Homestay' }}
                </p>
                <div class="flex items-center mt-2 space-x-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--color-leaf)]"></span>
                    <p class="text-xs text-[var(--color-paper-deep)] font-medium capitalize">{{ auth()->user()->name }} ({{ auth()->user()->role }})</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="px-4 py-4 space-y-1">
                @if (auth()->user()->isSuperAdmin())
                    <!-- Super Admin Routes -->
                    <a href="{{ route('super-admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition {{ request()->routeIs('super-admin.dashboard') ? 'bg-[var(--color-marigold-deep)] text-[#F6EFDC] shadow-md' : 'text-[var(--color-paper-deep)] hover:text-[#F6EFDC] hover:bg-[var(--color-teak)]/50' }}">
                        <span class="mr-3 text-lg">📊</span> Dashboard
                    </a>
                    <a href="{{ route('super-admin.homestays.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition {{ request()->routeIs('super-admin.homestays.*') ? 'bg-[var(--color-marigold-deep)] text-[#F6EFDC] shadow-md' : 'text-[var(--color-paper-deep)] hover:text-[#F6EFDC] hover:bg-[var(--color-teak)]/50' }}">
                        <span class="mr-3 text-lg">🏠</span> Kelola Homestay
                    </a>
                @else
                    <!-- Owner / Staff Routes -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-[var(--color-marigold-deep)] text-[#F6EFDC] shadow-md' : 'text-[var(--color-paper-deep)] hover:text-[#F6EFDC] hover:bg-[var(--color-teak)]/50' }}">
                        <span class="mr-3 text-lg">📊</span> Dashboard
                    </a>
                    <a href="{{ route('rooms.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition {{ request()->routeIs('rooms.*') ? 'bg-[var(--color-marigold-deep)] text-[#F6EFDC] shadow-md' : 'text-[var(--color-paper-deep)] hover:text-[#F6EFDC] hover:bg-[var(--color-teak)]/50' }}">
                        <span class="mr-3 text-lg">🛏️</span> Kelola Kamar
                    </a>
                    <a href="{{ route('guests.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition {{ request()->routeIs('guests.*') ? 'bg-[var(--color-marigold-deep)] text-[#F6EFDC] shadow-md' : 'text-[var(--color-paper-deep)] hover:text-[#F6EFDC] hover:bg-[var(--color-teak)]/50' }}">
                        <span class="mr-3 text-lg">👥</span> Data Tamu
                    </a>
                    <a href="{{ route('reservations.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition {{ request()->routeIs('reservations.*') ? 'bg-[var(--color-marigold-deep)] text-[#F6EFDC] shadow-md' : 'text-[var(--color-paper-deep)] hover:text-[#F6EFDC] hover:bg-[var(--color-teak)]/50' }}">
                        <span class="mr-3 text-lg">📅</span> Reservasi
                    </a>
                    <a href="{{ route('payments.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition {{ request()->routeIs('payments.*') ? 'bg-[var(--color-marigold-deep)] text-[#F6EFDC] shadow-md' : 'text-[var(--color-paper-deep)] hover:text-[#F6EFDC] hover:bg-[var(--color-teak)]/50' }}">
                        <span class="mr-3 text-lg">💵</span> Pembayaran
                    </a>

                    @if (auth()->user()->isOwner())
                        <a href="{{ route('staff.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition {{ request()->routeIs('staff.*') ? 'bg-[var(--color-marigold-deep)] text-[#F6EFDC] shadow-md' : 'text-[var(--color-paper-deep)] hover:text-[#F6EFDC] hover:bg-[var(--color-teak)]/50' }}">
                            <span class="mr-3 text-lg">🔑</span> Kelola Staff
                        </a>
                    @endif

                    <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition {{ request()->routeIs('reports.*') ? 'bg-[var(--color-marigold-deep)] text-[#F6EFDC] shadow-md' : 'text-[var(--color-paper-deep)] hover:text-[#F6EFDC] hover:bg-[var(--color-teak)]/50' }}">
                        <span class="mr-3 text-lg">📈</span> Laporan
                    </a>
                @endif
            </nav>
        </div>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-[var(--color-teak)] bg-[var(--color-teak)]/20">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl text-[var(--color-paper-deep)] hover:text-red-400 hover:bg-red-950/25 transition">
                <span class="mr-3 text-lg">🚪</span> Keluar
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="pl-64 flex-grow flex flex-col min-h-screen">
        <!-- Header Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-8 sticky top-0 z-10">
            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    @yield('header_title', 'StayNest Dashboard')
                </h2>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 uppercase font-semibold">{{ auth()->user()->role }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 font-extrabold flex items-center justify-center">
                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-grow p-8">
            <!-- Toast / Session Alerts -->
            @if (session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-100 p-4 shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0 text-emerald-500 font-bold">✓</div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-xl bg-red-50 border border-red-100 p-4 shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0 text-red-500 font-bold">⚠️</div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>