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
        <a href="{{ route('staff.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md shadow-indigo-600/10">
            + Tambah Akun Staff
        </a>
    </div>

    <!-- Staff Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @if ($staffs->isEmpty())
            <div class="text-center py-16 text-slate-400">
                <p class="text-lg">Belum ada staff terdaftar.</p>
                <p class="text-xs mt-1">Gunakan tombol di atas untuk mendaftarkan akun staff pertama Anda.</p>
            </div>
        @else
            <div class="overflow-x-auto">
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
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    {{ $staff->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $staff->email }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                        {{ ucfirst($staff->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('staff.destroy', $staff->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun staff ini? Staff tersebut tidak akan dapat masuk kembali ke sistem.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition text-xs font-bold">Hapus Akun</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-50">
                {{ $staffs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
