@section('title', 'Lupa Kata Sandi')

@extends('layouts.guest')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-6 px-4 sm:px-6 lg:px-8 bg-paper">
    <div class="sm:mx-auto w-full max-w-md">
        <h2 class="mt-6 text-center text-2xl sm:text-3xl font-bold tracking-tight text-ink">
            Lupa Kata Sandi
        </h2>
        <p class="mt-2 text-center text-sm text-ink-soft">
            Masukkan email Anda, kami akan kirim tautan reset.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto w-full max-w-md">
        <div class="py-6 px-4 sm:py-8 sm:px-10 shadow-xl rounded-2xl bg-ink border border-paper-deep text-panel">

            @if (session('success'))
                <div class="mb-6 rounded-lg p-4 bg-green-900 border border-green-800 text-green-100 text-xs">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg p-4 bg-red-900 border border-red-800 text-red-100">
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold mb-1 text-panel">Email</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}" inputmode="email" autocomplete="email" class="block w-full px-4 py-3 min-h-[44px] border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">
                </div>
                <button type="submit" class="w-full flex justify-center py-3 min-h-[44px] px-4 rounded-xl text-sm font-bold text-ink transition bg-panel hover:bg-paper">
                    Kirim Tautan Reset
                </button>
            </form>
            <div class="mt-6 text-center text-sm text-ink-soft">
                <a href="{{ route('login') }}" class="text-marigold hover:text-marigold-deep">Kembali ke halaman masuk</a>
            </div>
        </div>
    </div>
</div>
@endsection