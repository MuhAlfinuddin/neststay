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
    <nav class="bg-[#FFFBF1]/90 backdrop-blur-md sticky top-4 z-50 mt-4 mx-6 lg:mx-8 rounded-2xl shadow-lg border border-[#EEE1C3]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-2">
                        <span class="text-2xl font-black text-[#2B2013]">Stay<span class="text-[#C6863A]">Nest</span></span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('landing') }}#features" class="text-sm font-medium text-[#6B5A44] hover:text-[#C6863A] transition">Fitur</a>
                    <a href="{{ route('landing') }}#pricing" class="text-sm font-medium text-[#6B5A44] hover:text-[#C6863A] transition">Harga</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 h-9 text-sm font-bold border border-transparent bg-[#C6863A] text-[#FBF6E9] rounded-lg transition hover:bg-[#E3A857] shadow-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-[#6B5A44] hover:text-[#C6863A] transition">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 h-9 text-sm font-bold border border-transparent bg-[#C6863A] text-[#FBF6E9] rounded-lg transition hover:bg-[#E3A857] shadow-sm">Coba Gratis</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

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