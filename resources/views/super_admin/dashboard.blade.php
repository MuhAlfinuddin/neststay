@extends('layouts.app')

@section('header_title', 'Dasbor Super Admin')

@section('content')
<div class="space-y-8">
    <!-- Header Welcome -->
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">Dasbor Administrator StayNest</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola pendaftaran tenant homestay, status berlangganan, dan pantau metrik SaaS global.</p>
    </div>

    <!-- Metrics Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Tenant -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Homestay</span>
            <div class="mt-4">
                <h3 class="text-3xl font-black text-slate-950">{{ $totalHomestaysCount }}</h3>
                <p class="text-xs text-slate-400 mt-1">Terdaftar secara nasional</p>
            </div>
        </div>

        <!-- Active Tenant -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Homestay Aktif</span>
            <div class="mt-4">
                <h3 class="text-3xl font-black text-emerald-600">{{ $activeHomestaysCount }}</h3>
                <p class="text-xs text-slate-400 mt-1">Dapat mengakses sistem</p>
            </div>
        </div>

        <!-- Suspended Tenant -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tenant Ditangguhkan</span>
            <div class="mt-4">
                <h3 class="text-3xl font-black text-red-600">{{ $suspendedHomestaysCount }}</h3>
                <p class="text-xs text-slate-400 mt-1">Akses diblokir sementara</p>
            </div>
        </div>

        <!-- Total Users (Owners + Staffs) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pengguna Aktif</span>
            <div class="mt-4">
                <h3 class="text-3xl font-black text-indigo-600">{{ $totalOwnersCount + $totalStaffsCount }}</h3>
                <p class="text-xs text-slate-400 mt-1">{{ $totalOwnersCount }} Pemilik • {{ $totalStaffsCount }} Staff</p>
            </div>
        </div>
    </div>

    <!-- Tenant Management Section -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 mb-6">Manajemen Tenant / Registrasi Homestay</h3>

        @if ($homestays->isEmpty())
            <p class="text-slate-400 text-center py-12">Belum ada tenant yang terdaftar dalam platform SaaS StayNest.</p>
        @else
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead>
                        <tr class="text-left font-semibold text-slate-400 bg-slate-50/50">
                            <th class="px-6 py-4">Nama Homestay / Villa</th>
                            <th class="px-6 py-4">Pemilik (Owner)</th>
                            <th class="px-6 py-4">Kontak Telepon</th>
                            <th class="px-6 py-4">Tanggal Gabung</th>
                            <th class="px-6 py-4">Status Akun</th>
                            <th class="px-6 py-4 text-right">Tindakan Kontrol</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-medium text-slate-700 font-sans">
                        @foreach ($homestays as $homestay)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900">{{ $homestay->name }}</p>
                                    <p class="text-xs text-slate-400">Slug: {{ $homestay->slug }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @php $owner = $homestay->users->first(); @endphp
                                    @if ($owner)
                                        <p class="font-bold text-slate-800">{{ $owner->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $owner->email }}</p>
                                    @else
                                        <span class="text-xs text-red-500 font-bold">Tanpa Pemilik</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $homestay->phone ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $homestay->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if ($homestay->status === 'active') bg-emerald-50 text-emerald-700 @else bg-red-50 text-red-700 @endif">
                                        {{ ucfirst($homestay->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('super-admin.homestays.toggle-status', $homestay->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 min-h-[36px] text-xs font-bold rounded-lg transition border
                                            @if ($homestay->status === 'active') border-red-200 text-red-600 hover:bg-red-50 @else border-emerald-200 text-emerald-600 hover:bg-emerald-50 @endif">
                                            {{ $homestay->status === 'active' ? 'Tangguhkan (Suspend)' : 'Aktifkan (Activate)' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="block md:hidden divide-y divide-slate-50">
                @foreach ($homestays as $homestay)
                    <div class="p-4 space-y-2">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-grow min-w-0">
                                <h4 class="font-bold text-slate-900">{{ $homestay->name }}</h4>
                                <p class="text-xs text-slate-400">Slug: {{ $homestay->slug }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold shrink-0
                                @if ($homestay->status === 'active') bg-emerald-50 text-emerald-700 @else bg-red-50 text-red-700 @endif">
                                {{ ucfirst($homestay->status) }}
                            </span>
                        </div>
                        @php $owner = $homestay->users->first(); @endphp
                        @if ($owner)
                            <p class="text-xs text-slate-500">{{ $owner->name }} · {{ $owner->email }}</p>
                        @endif
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>{{ $homestay->phone ?? '-' }}</span>
                            <span>{{ $homestay->created_at->format('d M Y') }}</span>
                        </div>
                        <form action="{{ route('super-admin.homestays.toggle-status', $homestay->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2 min-h-[36px] text-xs font-bold rounded-lg transition border
                                @if ($homestay->status === 'active') border-red-200 text-red-600 hover:bg-red-50 @else border-emerald-200 text-emerald-600 hover:bg-emerald-50 @endif">
                                {{ $homestay->status === 'active' ? 'Tangguhkan (Suspend)' : 'Aktifkan (Activate)' }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-50">
                {{ $homestays->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
