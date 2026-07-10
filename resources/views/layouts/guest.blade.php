<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'StayNest - SaaS Manajemen Homestay' }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --paper:#F6EFDC;
            --paper-deep:#EEE1C3;
            --panel:#FFFBF1;
            --teak:#6B4226;
            --teak-deep:#4A2D19;
            --marigold:#E3A857;
            --marigold-deep:#C6863A;
            --ink:#2B2013;
            --ink-soft:#6B5A44;
        }
        body {
            background-color: var(--paper);
            color: var(--ink);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F6EFDC] text-[#2B2013] selection:bg-[#E3A857] selection:text-[#2B2013] min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-[#FFFBF1]/90 backdrop-blur-md sticky top-4 z-50 mt-4 mx-4 md:mx-6 lg:mx-8 rounded-2xl shadow-lg border border-[#EEE1C3]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center h-16">
                <!-- Brand -->
                <div class="flex items-center">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-2">
                        <span class="text-2xl font-black text-[#2B2013]">Stay<span class="text-[#C6863A]">Nest</span></span>
                    </a>
                </div>

                <!-- Desktop Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('landing') }}#features" class="text-sm font-medium text-[#6B5A44] hover:text-[#C6863A] transition">Fitur</a>
                    <a href="{{ route('landing') }}#pricing" class="text-sm font-medium text-[#6B5A44] hover:text-[#C6863A] transition">Harga</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 h-9 text-sm font-bold border border-transparent bg-[#C6863A] text-[#FBF6E9] rounded-lg transition hover:bg-[#E3A857] shadow-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-[#6B5A44] hover:text-[#C6863A] transition">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 h-9 text-sm font-bold border border-transparent bg-[#C6863A] text-[#FBF6E9] rounded-lg transition hover:bg-[#E3A857] shadow-sm">Coba Gratis</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobileMenuToggle" class="p-3 min-h-[44px] min-w-[44px] flex items-center justify-center text-[#6B5A44] hover:text-[#C6863A] focus:outline-none" aria-label="Toggle menu">
                        <svg id="guestHamburger" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="guestClose" class="h-8 w-8 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Animated slide-down) -->
        <div id="mobileMenu" class="md:hidden overflow-hidden transition-all duration-300 max-h-0 bg-[#FFFBF1] border-t border-[#EEE1C3] rounded-b-2xl">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('landing') }}#features" class="block py-3 min-h-[44px] text-sm font-medium text-[#6B5A44] hover:text-[#C6863A] flex items-center">Fitur</a>
                <a href="{{ route('landing') }}#pricing" class="block py-3 min-h-[44px] text-sm font-medium text-[#6B5A44] hover:text-[#C6863A] flex items-center">Harga</a>
                @guest
                    <hr class="border-[#EEE1C3] my-1">
                    <a href="{{ route('login') }}" class="block py-3 min-h-[44px] text-sm font-semibold text-[#6B5A44] hover:text-[#C6863A] flex items-center">Masuk</a>
                    <a href="{{ route('register') }}" class="block py-3 min-h-[44px] text-sm font-bold text-[#C6863A] hover:text-[#E3A857] flex items-center">Coba Gratis</a>
                @endguest
            </div>
        </div>
    </nav>

    <script>
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            var menu = document.getElementById('mobileMenu');
            var isOpen = menu.classList.contains('max-h-0');
            menu.classList.toggle('max-h-0', !isOpen);
            menu.classList.toggle('max-h-96', isOpen);
            document.getElementById('guestHamburger').classList.toggle('hidden', isOpen);
            document.getElementById('guestClose').classList.toggle('hidden', !isOpen);
        });
    </script>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#2B2013] text-[#D8C9A3] py-12 border-t border-[#4A2D19]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-2">
                    <span class="text-xl font-black text-[#F6EFDC]">Stay<span class="text-[#E3A857]">Nest</span></span>
                    <span class="text-sm">© {{ date('Y') }} Platform Manajemen Homestay SaaS.</span>
                </div>
                <div class="flex space-x-6 text-sm">
                    <a href="#" class="hover:text-[#E3A857] transition">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-[#E3A857] transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-[#E3A857] transition">Bantuan</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>