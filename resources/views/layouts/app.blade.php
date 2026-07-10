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
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-20 w-64 bg-[var(--color-teak-deep)] text-[#F6EFDC] flex flex-col justify-between border-r border-[var(--color-teak)] hidden md:flex">
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
                    <a href="{{ route('super-admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('super-admin.dashboard') ? 'bg-[var(--color-marigold-deep)] text-white shadow-lg ring-1 ring-white/10' : 'text-[var(--color-paper-deep)] hover:text-white hover:bg-[var(--color-teak)]/70' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg> Dashboard
                    </a>
                    <a href="{{ route('super-admin.homestays.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('super-admin.homestays.*') ? 'bg-[var(--color-marigold-deep)] text-white shadow-lg ring-1 ring-white/10' : 'text-[var(--color-paper-deep)] hover:text-white hover:bg-[var(--color-teak)]/70' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg> Kelola Homestay
                    </a>
                @else
                    <!-- Owner / Staff Routes -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-[var(--color-marigold-deep)] text-white shadow-lg ring-1 ring-white/10' : 'text-[var(--color-paper-deep)] hover:text-white hover:bg-[var(--color-teak)]/70' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg> Dashboard
                    </a>
                    <a href="{{ route('rooms.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('rooms.*') ? 'bg-[var(--color-marigold-deep)] text-white shadow-lg ring-1 ring-white/10' : 'text-[var(--color-paper-deep)] hover:text-white hover:bg-[var(--color-teak)]/70' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg> Kelola Kamar
                    </a>
                    <a href="{{ route('guests.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('guests.*') ? 'bg-[var(--color-marigold-deep)] text-white shadow-lg ring-1 ring-white/10' : 'text-[var(--color-paper-deep)] hover:text-white hover:bg-[var(--color-teak)]/70' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg> Data Tamu
                    </a>
                    <a href="{{ route('reservations.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('reservations.*') ? 'bg-[var(--color-marigold-deep)] text-white shadow-lg ring-1 ring-white/10' : 'text-[var(--color-paper-deep)] hover:text-white hover:bg-[var(--color-teak)]/70' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> Reservasi
                    </a>
                    <a href="{{ route('payments.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('payments.*') ? 'bg-[var(--color-marigold-deep)] text-white shadow-lg ring-1 ring-white/10' : 'text-[var(--color-paper-deep)] hover:text-white hover:bg-[var(--color-teak)]/70' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg> Pembayaran
                    </a>

                    @if (auth()->user()->isOwner())
                        <a href="{{ route('staff.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('staff.*') ? 'bg-[var(--color-marigold-deep)] text-white shadow-lg ring-1 ring-white/10' : 'text-[var(--color-paper-deep)] hover:text-white hover:bg-[var(--color-teak)]/70' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg> Kelola Staff
                        </a>
                    @endif

                    <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-[var(--color-marigold-deep)] text-white shadow-lg ring-1 ring-white/10' : 'text-[var(--color-paper-deep)] hover:text-white hover:bg-[var(--color-teak)]/70' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg> Laporan
                    </a>
                @endif
            </nav>
        </div>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-[var(--color-teak)] bg-[var(--color-teak)]/20">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl text-[var(--color-paper-deep)] hover:text-red-400 hover:bg-red-950/25 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg> Keluar
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="md:pl-64 flex-grow flex flex-col min-h-screen min-w-0 w-full">
        <!-- Header Top Navbar -->
        <header class="h-16 bg-[var(--color-paper)]/70 backdrop-blur-md border-b border-[var(--color-teak)]/30 rounded-b-2xl flex items-center justify-between px-4 md:px-8 sticky top-0 z-10">
            <div class="flex items-center">
                <h2 class="text-lg font-bold text-[var(--color-teak-deep)]">
                    @yield('header_title', 'StayNest Dashboard')
                </h2>
            </div>
            <div class="flex items-center space-x-4">
                <button id="sidebarToggle" class="md:hidden mr-4 p-2 text-[var(--color-teak-deep)] focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="text-right hidden md:block">
                    <p class="text-sm font-bold text-[var(--color-teak-deep)]">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-[var(--color-ink-soft)] uppercase font-semibold">{{ auth()->user()->role }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-[var(--color-teak-deep)]/10 text-[var(--color-teak-deep)] font-extrabold flex items-center justify-center">
                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-grow p-4 sm:p-6 md:p-8">
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
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('hidden');
        });
    </script>
</body>
</html>