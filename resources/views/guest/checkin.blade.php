<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Tamu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 p-4 sm:p-6">
    <div class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <!-- Logo -->
        <div class="text-center mb-6">
             <span class="text-2xl font-black text-[var(--color-teak-deep)]">Stay<span class="text-[var(--color-leaf)]">Nest</span></span>
        </div>

        <h1 class="text-xl font-black text-slate-900 mb-2">Check-in Digital</h1>
        <p class="text-slate-500 text-sm mb-6">Halo {{ $reservation->guest->name }}, silakan selesaikan data check-in Anda.</p>

        <!-- Reservation Details -->
        <div class="bg-slate-50 rounded-xl p-4 mb-6 border border-slate-100">
            <h3 class="text-xs font-bold text-slate-400 uppercase mb-2">Detail Reservasi</h3>
            <p class="text-sm font-bold text-slate-800">Kamar {{ $reservation->room->room_number }} ({{ $reservation->room->room_type }})</p>
            <p class="text-xs text-slate-600 mt-1">{{ $reservation->check_in->format('d M Y') }} - {{ $reservation->check_out->format('d M Y') }}</p>
        </div>

        <form action="{{ route('checkin.store', $reservation->checkin_token) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Foto KTP</label>
                <div class="relative">
                    <input type="file" name="ktp_photo" id="ktp_photo" class="hidden" onchange="document.getElementById('file-name').textContent = this.files[0].name" required>
                    <label for="ktp_photo" class="cursor-pointer block w-full py-4 px-4 bg-white border-2 border-dashed border-slate-300 rounded-xl text-center text-sm text-slate-500 hover:border-[var(--color-marigold-deep)] hover:text-[var(--color-teak-deep)] transition">
                        <span class="block mb-1">📸 Klik untuk pilih foto KTP</span>
                        <span id="file-name" class="font-bold text-xs text-slate-700">Belum ada file dipilih</span>
                    </label>
                </div>
            </div>
            <button type="submit" class="w-full py-4 bg-slate-900 text-white font-black text-base rounded-xl transition shadow-lg">
                Submit KTP Sekarang
            </button>
        </form>
    </div>
</body>
</html>