@extends('layouts.app')

@section('title', 'Safe Report - SahabatKelas')

@section('content')
<div class="max-w-3xl mx-auto mb-10">

    <!-- Header -->
    <div class="bg-teal-50 border border-teal-100 rounded-2xl p-6 mb-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-teal-800">Safe Report</h1>
        </div>
        <p class="text-teal-700 text-sm leading-relaxed">
            Ruang aman untuk melaporkan kejadian perundungan atau hal yang membuatmu tidak nyaman. Laporanmu akan ditangani secara rahasia oleh pihak sekolah yang berwenang.
        </p>
    </div>

    <!-- Menampilkan pesan error validasi jika ada -->
    @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm mb-6 border border-red-100">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('siswa.report.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Detail Kejadian -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-5 border-b border-gray-100 pb-2">Konteks Kejadian</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pelapor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kamu melaporkan sebagai siapa?</label>
                    <select name="pelapor" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none text-sm" required>
                        <option value="" disabled selected>Pilih peranmu...</option>
                        <option value="korban">Korban (Saya mengalami sendiri)</option>
                        <option value="saksi">Saksi (Saya melihat orang lain mengalami)</option>
                    </select>
                </div>

                <!-- Jenis -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis kejadian yang paling mendekati?</label>
                    <select name="jenis" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none text-sm" required>
                        <option value="" disabled selected>Pilih jenis kejadian...</option>
                        <option value="fisik">Fisik (Pukulan, dorongan, merusak barang)</option>
                        <option value="verbal">Verbal (Ejekan, hinaan, ancaman kata-kata)</option>
                        <option value="sosial">Sosial (Pengucilan, menyebarkan rumor)</option>
                        <option value="siber">Siber (Lewat media sosial, pesan digital)</option>
                    </select>
                </div>

                <!-- Lokasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Di mana kejadian ini terjadi?</label>
                    <select name="lokasi" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none text-sm" required>
                        <option value="" disabled selected>Pilih lokasi...</option>
                        <option value="lingkungan_sekolah">Lingkungan Sekolah</option>
                        <option value="luar_sekolah">Luar Sekolah</option>
                        <option value="dunia_maya">Dunia Maya (Internet/Sosmed)</option>
                    </select>
                </div>

                <!-- Waktu -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kapan kejadian biasanya terjadi?</label>
                    <select name="waktu" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none text-sm" required>
                        <option value="" disabled selected>Pilih waktu...</option>
                        <option value="jam_pelajaran">Saat Jam Pelajaran</option>
                        <option value="istirahat">Saat Jam Istirahat</option>
                        <option value="pulang_sekolah">Saat/Setelah Pulang Sekolah</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Pertanyaan Kondisional (Ya/Tidak) -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-5 border-b border-gray-100 pb-2">Informasi Tambahan</h2>
            
            <div class="space-y-5">
                <!-- Berulang -->
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-700">Apakah kejadian ini terjadi berulang kali?</p>
                    <div class="flex gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="berulang" value="ya" class="peer sr-only" required>
                            <div class="py-1.5 px-4 rounded-lg border border-gray-200 text-sm peer-checked:bg-teal-50 peer-checked:border-teal-500 peer-checked:text-teal-700 hover:bg-gray-50">Ya</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="berulang" value="tidak" class="peer sr-only">
                            <div class="py-1.5 px-4 rounded-lg border border-gray-200 text-sm peer-checked:bg-teal-50 peer-checked:border-teal-500 peer-checked:text-teal-700 hover:bg-gray-50">Tidak</div>
                        </label>
                    </div>
                </div>

                <!-- Rasa Tidak Aman -->
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-700">Apakah kejadian ini membuatmu merasa tidak aman?</p>
                    <div class="flex gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="rasa_tidak_aman" value="ya" class="peer sr-only" required>
                            <div class="py-1.5 px-4 rounded-lg border border-gray-200 text-sm peer-checked:bg-orange-50 peer-checked:border-orange-500 peer-checked:text-orange-700 hover:bg-gray-50">Ya</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="rasa_tidak_aman" value="tidak" class="peer sr-only">
                            <div class="py-1.5 px-4 rounded-lg border border-gray-200 text-sm peer-checked:bg-teal-50 peer-checked:border-teal-500 peer-checked:text-teal-700 hover:bg-gray-50">Tidak</div>
                        </label>
                    </div>
                </div>

                <!-- Saksi -->
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-700">Apakah ada orang lain yang menyaksikan?</p>
                    <div class="flex gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="saksi" value="ada" class="peer sr-only" required>
                            <div class="py-1.5 px-4 rounded-lg border border-gray-200 text-sm peer-checked:bg-teal-50 peer-checked:border-teal-500 peer-checked:text-teal-700 hover:bg-gray-50">Ada</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="saksi" value="tidak_ada" class="peer sr-only">
                            <div class="py-1.5 px-4 rounded-lg border border-gray-200 text-sm peer-checked:bg-teal-50 peer-checked:border-teal-500 peer-checked:text-teal-700 hover:bg-gray-50">Tidak ada</div>
                        </label>
                    </div>
                </div>

                <!-- Prioritas (Dibutuhkan oleh Controller) -->
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-700">Tingkat keparahan/urgensi laporan ini menurutmu?</p>
                    <div class="flex gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="prioritas" value="rendah" class="peer sr-only" required>
                            <div class="py-1.5 px-3 rounded-lg border border-gray-200 text-xs peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 hover:bg-gray-50">Biasa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="prioritas" value="sedang" class="peer sr-only">
                            <div class="py-1.5 px-3 rounded-lg border border-gray-200 text-xs peer-checked:bg-yellow-50 peer-checked:border-yellow-500 peer-checked:text-yellow-700 hover:bg-gray-50">Sedang</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="prioritas" value="tinggi" class="peer sr-only">
                            <div class="py-1.5 px-3 rounded-lg border border-gray-200 text-xs peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 hover:bg-gray-50">Mendesak</div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Narasi / Cerita Bebas -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <label for="komentar" class="block text-lg font-semibold text-gray-800 mb-1">Ceritakan kejadian yang kamu alami atau lihat</label>
            <p class="text-sm text-gray-500 mb-4">Ceritakan sedetail mungkin (siapa yang terlibat, apa yang terjadi). Sistem cerdas kami akan membantu menganalisis laporanmu agar sekolah dapat mengambil tindakan yang paling tepat.</p>
            <textarea name="komentar" id="komentar" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-colors text-sm" placeholder="Ketik laporanmu di sini (minimal 10 karakter)..." required minlength="10"></textarea>
        </div>

        <!-- Anonimitas & Tombol Kirim -->
        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-start flex-1">
                <div class="flex items-center h-5">
                    <input id="anonim" name="anonim" type="checkbox" value="1" class="w-4 h-4 text-teal-600 bg-white border-gray-300 rounded focus:ring-teal-500 focus:ring-2">
                </div>
                <div class="ml-3 text-sm">
                    <label for="anonim" class="font-medium text-gray-700">Sembunyikan identitas saya</label>
                    <p class="text-gray-500 text-xs mt-0.5">Nama kamu tidak akan ditampilkan secara langsung pada halaman laporan awal di guru BK.</p>
                </div>
            </div>
            
            <button type="submit" class="w-full sm:w-auto bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-8 rounded-xl transition-colors shadow-sm text-sm whitespace-nowrap">
                Kirim Laporan
            </button>
        </div>
    </form>
</div>
@endsection