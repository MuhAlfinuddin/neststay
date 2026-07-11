@section('title', 'Reset Kata Sandi')

@extends('layouts.guest')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-6 px-4 sm:px-6 lg:px-8 bg-paper">
    <div class="sm:mx-auto w-full max-w-md">
        <h2 class="mt-6 text-center text-2xl sm:text-3xl font-bold tracking-tight text-ink">
            Reset Kata Sandi
        </h2>
        <p class="mt-2 text-center text-sm text-ink-soft">
            Buat kata sandi baru untuk akun Anda.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto w-full max-w-md">
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

            <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label for="email" class="block text-sm font-semibold mb-1 text-panel">Email</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}" inputmode="email" autocomplete="email" class="block w-full px-4 py-3 min-h-[44px] border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold mb-1 text-panel">Kata Sandi Baru</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password" class="block w-full px-4 py-3 min-h-[44px] border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold mb-1 text-panel">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="block w-full px-4 py-3 min-h-[44px] border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">
                </div>
                <button type="submit" class="w-full flex justify-center py-3 min-h-[44px] px-4 rounded-xl text-sm font-bold text-ink transition bg-panel hover:bg-paper">
                    Reset Kata Sandi
                </button>
            </form>
            <div class="mt-6 text-center text-sm text-ink-soft">
                <a href="{{ route('login') }}" class="text-marigold hover:text-marigold-deep">Kembali ke halaman masuk</a>
            </div>
        </div>
    </div>
</div>
@endsection