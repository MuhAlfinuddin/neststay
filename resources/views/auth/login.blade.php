@section('title', 'Login')

@extends('layouts.guest')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8" style="background-color: var(--paper);">
    <div class="sm:mx-auto w-full max-w-md">
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight" style="color: var(--ink);">
            Masuk ke Akun StayNest
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto w-full max-w-md">
        <div class="py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10" style="background-color: var(--panel); border: 1px solid var(--paper-deep);">
            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold mb-1" style="color: var(--ink);">Alamat Email</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}" class="block w-full px-4 py-2 border rounded-xl focus:outline-none" style="border: 1px solid var(--ink-soft); background: transparent; color: var(--ink);">
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold mb-1" style="color: var(--ink);">Password</label>
                    <input id="password" name="password" type="password" required class="block w-full px-4 py-2 border rounded-xl focus:outline-none" style="border: 1px solid var(--ink-soft); background: transparent; color: var(--ink);">
                </div>
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-xl text-sm font-bold text-[#FBF6E9] transition" style="background-color: var(--marigold-deep);">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
