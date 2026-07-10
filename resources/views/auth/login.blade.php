@section('title', 'Login')

@extends('layouts.guest')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-6 px-4 sm:px-6 lg:px-8 bg-paper">
    <div class="sm:mx-auto w-full max-w-md">
        <h2 class="mt-6 text-center text-2xl sm:text-3xl font-bold tracking-tight text-ink">
            Login to your account
        </h2>
        <p class="mt-2 text-center text-sm text-ink-soft">
            Enter your email below to login to your account
        </p>
    </div>

    <div class="mt-8 sm:mx-auto w-full max-w-md">
        <div class="py-6 px-4 sm:py-8 sm:px-10 shadow-xl sm:rounded-2xl bg-ink border border-paper-deep text-panel">
            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold mb-1 text-panel">Email</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}" inputmode="email" autocomplete="email" class="block w-full px-4 py-3 min-h-[44px] border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">
                </div>
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-semibold mb-1 text-panel">Password</label>
                        <a href="#" class="text-sm text-marigold hover:text-marigold-deep">Forgot your password?</a>
                    </div>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="block w-full px-4 py-3 min-h-[44px] border rounded-xl focus:outline-none border-ink-soft bg-ink-soft text-panel text-[16px]">
                </div>
                <button type="submit" class="w-full flex justify-center py-3 min-h-[44px] px-4 rounded-xl text-sm font-bold text-ink transition bg-panel hover:bg-paper">
                    Login
                </button>
                <button type="button" class="w-full flex justify-center py-3 min-h-[44px] px-4 rounded-xl text-sm font-bold text-panel transition bg-transparent border border-ink-soft hover:bg-ink-soft">
                    Login with Google
                </button>
            </form>
            <div class="mt-6 text-center text-sm text-ink-soft">
                Don't have an account? <a href="{{ route('register') }}" class="text-marigold hover:text-marigold-deep">Sign up</a>
            </div>
        </div>
    </div>
</div>
@endsection
