@extends('layouts.app')

@section('header_title', 'Kelola Staff Homestay')

@section('content')
<div class="space-y-6">
    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Kelola Akun Staff</h1>
            <p class="text-xs text-slate-500 mt-1">Daftarkan akun login khusus staff operasional homestay Anda.</p>
        </div>
        <a href="{{ route('staff.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-[var(--color-marigold-deep)] hover:bg-[var(--color-teak-deep)] rounded-xl transition shadow-md shadow-indigo-600/10">
            + Tambah Akun Staff
        </a>
    </div>

    <!-- Staff Table / Card List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        @if ($staffs->isEmpty())
            <div class="text-center py-16 text-slate-400">
                <p class="text-lg">Belum ada staff terdaftar.</p>
                <p class="text-xs mt-1">Gunakan tombol di atas untuk mendaftarkan akun staff pertama Anda.</p>
            </div>
        @else
            <!-- Desktop Table -->
            <div class="hidden md:block w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead>
                        <tr class="text-left font-semibold text-slate-400 bg-slate-50/50">
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">Alamat Email (Username)</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-medium text-slate-700">
                        @foreach ($staffs as $staff)
                            <tr>
                                <td class="px-6 py-4 font-bold text-slate-900">{{ $staff->name }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $staff->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">{{ ucfirst($staff->role) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('staff.destroy', $staff->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun staff ini? Staff tersebut tidak akan dapat masuk kembali ke sistem.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 min-h-[36px] text-xs font-bold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">Hapus Akun</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="block md:hidden divide-y divide-slate-50">
                @foreach ($staffs as $staff)
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-extrabold flex items-center justify-center shrink-0 text-sm">
                            {{ strtoupper(mb_substr($staff->name, 0, 2)) }}
                        </div>
                        <div class="flex-grow min-w-0">
                            <h4 class="font-bold text-slate-900">{{ $staff->name }}</h4>
                            <p class="text-xs text-slate-500 truncate">{{ $staff->email }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 mt-1">{{ ucfirst($staff->role) }}</span>
                        </div>
                        <div class="shrink-0">
                            <form action="{{ route('staff.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun staff ini? Staff tersebut tidak akan dapat masuk kembali ke sistem.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center px-3 min-h-[36px] text-xs font-bold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-50">
                {{ $staffs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
