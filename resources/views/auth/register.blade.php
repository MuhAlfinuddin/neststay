@section('title', 'Register')

@extends('layouts.guest')

@section('content')
<div class="min-h-[90vh] flex flex-col justify-center py-6 px-4 sm:px-6 lg:px-8" style="background-color: var(--paper);">
    <div class="sm:mx-auto w-full max-w-lg">
        <h2 class="mt-6 text-center text-2xl sm:text-3xl font-bold tracking-tight" style="color: var(--ink);">
            Daftarkan Homestay Anda di StayNest
        </h2>
        <p class="mt-2 text-center text-sm" style="color: var(--ink-soft);">
            Atau
            <a href="{{ route('login') }}" style="color: var(--marigold-deep); font-weight: 600;">
                masuk jika sudah memiliki akun
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto w-full max-w-lg">
        <div class="py-6 px-4 sm:py-8 sm:px-10 shadow-xl sm:rounded-2xl" style="background-color: var(--panel); border: 1px solid var(--paper-deep);">
            @if ($errors->any())
                <div class="mb-6 rounded-lg p-4" style="background-color: #FEF2F2; border: 1px solid #FEE2E2; color: #991B1B;">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span style="color: #EF4444; font-weight: 700;">⚠️</span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium">Terdapat beberapa kesalahan:</h3>
                            <ul class="mt-2 list-disc list-inside text-xs space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" value="{{ request('plan', 'hemat') }}">

                <div class="pb-6" style="border-bottom: 1px solid var(--paper-deep);">
                    <h3 class="text-md font-bold mb-4" style="color: var(--ink);">1. Data Pemilik (Owner)</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-semibold mb-1" style="color: var(--ink);">Nama Lengkap</label>
                            <input id="name" name="name" type="text" required value="{{ old('name') }}" style="border: 1px solid var(--ink-soft); background: transparent; color: var(--ink);" class="block w-full px-4 py-2 border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold mb-1" style="color: var(--ink);">Alamat Email</label>
                            <input id="email" name="email" type="email" required value="{{ old('email') }}" style="border: 1px solid var(--ink-soft); background: transparent; color: var(--ink);" class="block w-full px-4 py-2 border rounded-xl focus:outline-none">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-semibold mb-1" style="color: var(--ink);">Password</label>
                                <input id="password" name="password" type="password" required style="border: 1px solid var(--ink-soft); background: transparent; color: var(--ink);" class="block w-full px-4 py-2 border rounded-xl focus:outline-none">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold mb-1" style="color: var(--ink);">Konfirmasi Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required style="border: 1px solid var(--ink-soft); background: transparent; color: var(--ink);" class="block w-full px-4 py-2 border rounded-xl focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <h3 class="text-md font-bold mb-4" style="color: var(--ink);">2. Data Homestay</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="homestay_name" class="block text-sm font-semibold mb-1" style="color: var(--ink);">Nama Homestay / Villa</label>
                            <input id="homestay_name" name="homestay_name" type="text" required value="{{ old('homestay_name') }}" style="border: 1px solid var(--ink-soft); background: transparent; color: var(--ink);" class="block w-full px-4 py-2 border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label for="homestay_phone" class="block text-sm font-semibold mb-1" style="color: var(--ink);">Nomor Telepon Homestay</label>
                            <input id="homestay_phone" name="homestay_phone" type="text" required value="{{ old('homestay_phone') }}" placeholder="Contoh: 08123456789" style="border: 1px solid var(--ink-soft); background: transparent; color: var(--ink);" class="block w-full px-4 py-2 border rounded-xl focus:outline-none">
                        </div>
                        <div>
                            <label for="homestay_address" class="block text-sm font-semibold mb-1" style="color: var(--ink);">Alamat Lengkap</label>
                            <textarea id="homestay_address" name="homestay_address" rows="3" required style="border: 1px solid var(--ink-soft); background: transparent; color: var(--ink);" class="block w-full px-4 py-2 border rounded-xl focus:outline-none">{{ old('homestay_address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-xl text-sm font-bold text-[#FBF6E9] transition" style="background-color: var(--marigold-deep);">
                        Daftar & Buat Homestay
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
