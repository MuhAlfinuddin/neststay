@section('title', 'Daftar')

@extends('layouts.guest')

@section('content')
<div class="min-h-[90vh] flex flex-col justify-center py-6 px-4 sm:px-6 lg:px-8 bg-paper">
    <div class="sm:mx-auto w-full max-w-lg">
        <h2 class="mt-6 text-center text-2xl sm:text-3xl font-bold tracking-tight text-ink">
            Daftarkan Homestay Anda
        </h2>
        <p class="mt-2 text-center text-sm text-ink-soft">
            Isi data diri dan data homestay Anda
        </p>
    </div>

    <div class="mt-8 sm:mx-auto w-full max-w-lg">
        <div class="py-6 px-4 sm:py-8 sm:px-10 shadow-xl rounded-2xl bg-ink border border-paper-deep text-panel">
            @if ($errors->any())
                <div class="mb-6 rounded-lg p-4 bg-red-900 border border-red-800 text-red-100">
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" value="{{ request('plan', 'hemat') }}">

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold mb-1 text-panel">Nama Lengkap</label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}" autocomplete="name" class="block w-full px-4 py-3 min-h-[44px] border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold mb-1 text-panel">Email</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" inputmode="email" autocomplete="email" class="block w-full px-4 py-3 min-h-[44px] border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">
                        <p class="mt-1 text-xs text-ink-soft">Kami akan gunakan ini untuk menghubungi Anda. Email tidak akan dibagikan.</p>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-semibold mb-1 text-panel">Kata Sandi</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password" class="block w-full px-4 py-3 min-h-[44px] border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">
                        <p class="mt-1 text-xs text-ink-soft">Minimal 8 karakter.</p>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold mb-1 text-panel">Konfirmasi Kata Sandi</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="block w-full px-4 py-3 min-h-[44px] border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">
                        <p class="mt-1 text-xs text-ink-soft">Harap konfirmasi kata sandi Anda.</p>
                    </div>

                    <hr class="border-ink-soft/30 my-2">

                    <h3 class="text-sm font-bold text-panel/80">2. Data Homestay</h3>

                    <div>
                        <label for="homestay_name" class="block text-sm font-semibold mb-1 text-panel">Nama Homestay / Villa</label>
                        <input id="homestay_name" name="homestay_name" type="text" required value="{{ old('homestay_name') }}" class="block w-full px-4 py-3 min-h-[44px] border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">
                    </div>
                    <div>
                        <label for="homestay_phone" class="block text-sm font-semibold mb-1 text-panel">Nomor Telepon Homestay</label>
                        <input id="homestay_phone" name="homestay_phone" type="tel" required value="{{ old('homestay_phone') }}" placeholder="Contoh: 08123456789" class="block w-full px-4 py-3 min-h-[44px] border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">
                    </div>
                    <div>
                        <label for="homestay_address" class="block text-sm font-semibold mb-1 text-panel">Alamat Lengkap</label>
                        <textarea id="homestay_address" name="homestay_address" rows="3" required class="block w-full px-4 py-3 border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">{{ old('homestay_address') }}</textarea>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 min-h-[44px] px-4 rounded-xl text-sm font-bold text-ink transition bg-panel hover:bg-paper">
                        Daftar & Buat Homestay
                    </button>
                </div>
            </form>
            <div class="mt-6 text-center text-sm text-ink-soft">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-marigold hover:text-marigold-deep">Masuk</a>
            </div>
        </div>
    </div>
</div>
@endsection
