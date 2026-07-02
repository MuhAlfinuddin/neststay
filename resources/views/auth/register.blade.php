@extends('layouts.guest')

@section('content')
<div class="min-h-[90vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-slate-50">
    <div class="sm:mx-auto w-full max-w-lg">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900 tracking-tight">
            Daftarkan Homestay Anda di StayNest
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Atau
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition">
                masuk jika sudah memiliki akun
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto w-full max-w-lg">
        <div class="bg-white py-8 px-4 shadow-sm border border-slate-100 sm:rounded-2xl sm:px-10">
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-100">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-red-500 font-bold">⚠️</span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                            <ul class="mt-2 list-disc list-inside text-xs text-red-700 space-y-1">
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

                <!-- Owner Account Details -->
                <div class="border-b border-slate-100 pb-6">
                    <h3 class="text-md font-bold text-slate-800 mb-4">1. Data Pemilik (Owner)</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                            <div class="mt-1">
                                <input id="name" name="name" type="text" required value="{{ old('name') }}" class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700">Alamat Email</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" required value="{{ old('email') }}" class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                                <div class="mt-1">
                                    <input id="password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                                <div class="mt-1">
                                    <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Homestay Details -->
                <div class="pt-2">
                    <h3 class="text-md font-bold text-slate-800 mb-4">2. Data Homestay</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="homestay_name" class="block text-sm font-semibold text-slate-700">Nama Homestay / Villa</label>
                            <div class="mt-1">
                                <input id="homestay_name" name="homestay_name" type="text" required value="{{ old('homestay_name') }}" class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="homestay_phone" class="block text-sm font-semibold text-slate-700">Nomor Telepon Homestay</label>
                            <div class="mt-1">
                                <input id="homestay_phone" name="homestay_phone" type="text" required value="{{ old('homestay_phone') }}" placeholder="Contoh: 08123456789" class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="homestay_address" class="block text-sm font-semibold text-slate-700">Alamat Lengkap</label>
                            <div class="mt-1">
                                <textarea id="homestay_address" name="homestay_address" rows="3" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('homestay_address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        Daftar & Buat Homestay
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
