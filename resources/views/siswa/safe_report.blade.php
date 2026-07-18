@extends('layouts.app')

@section('title', 'Safe Report - SahabatKelas')

@section('content')
<div class="max-w-3xl mx-auto mb-10">

    <!-- Header -->
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-3xl p-8 mb-8 text-white shadow-xl shadow-amber-200/50 relative overflow-hidden">
        <!-- Dekorasi -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-48 h-48 bg-white opacity-20 rounded-full blur-3xl dark:hidden"></div>
        <div class="absolute bottom-0 right-20 -mb-10 w-32 h-32 bg-orange-300 opacity-30 rounded-full blur-2xl dark:hidden"></div>

        <div class="relative z-10 flex items-center gap-4 mb-3">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-md text-white rounded-2xl flex items-center justify-center border border-white/30 shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black tracking-tight">Safe Report</h1>
        </div>
        <p class="text-amber-50 text-base leading-relaxed max-w-2xl relative z-10">
            Ruang aman untuk melaporkan kejadian perundungan atau hal yang membuatmu tidak nyaman. Laporanmu akan ditangani secara rahasia oleh pihak sekolah yang berwenang.
        </p>
    </div>

    <!-- Menampilkan pesan error validasi jika ada -->
    @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-5 rounded-2xl text-sm mb-8 border border-red-100 shadow-sm">
            <ul class="list-disc pl-5 space-y-1 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('siswa.report.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- Detail Kejadian -->
        <div class="bg-white p-7 rounded-3xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-lg hover:border-amber-100">
            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold">1</span>
                <h2 class="text-xl font-bold text-gray-800">Konteks Kejadian</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pelapor -->
                <div class="group">
                    <label class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-amber-600 transition-colors">Kamu melaporkan sebagai siapa?</label>
                    <select name="pelapor" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none text-sm transition-all hover:border-amber-300 bg-gray-50 focus:bg-white" required>
                        <option value="" disabled selected>Pilih peranmu...</option>
                        <option value="korban">Korban (Saya mengalami sendiri)</option>
                        <option value="saksi">Saksi (Saya melihat orang lain mengalami)</option>
                    </select>
                </div>

                <!-- Jenis -->
                <div class="group">
                    <label class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-amber-600 transition-colors">Jenis kejadian yang paling mendekati?</label>
                    <select name="jenis" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none text-sm transition-all hover:border-amber-300 bg-gray-50 focus:bg-white" required>
                        <option value="" disabled selected>Pilih jenis kejadian...</option>
                        <option value="fisik">Fisik (Pukulan, dorongan, merusak barang)</option>
                        <option value="verbal">Verbal (Ejekan, hinaan, ancaman kata-kata)</option>
                        <option value="sosial">Sosial (Pengucilan, menyebarkan rumor)</option>
                        <option value="siber">Siber (Lewat media sosial, pesan digital)</option>
                    </select>
                </div>

                <!-- Lokasi -->
                <div class="group">
                    <label class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-amber-600 transition-colors">Di mana kejadian ini terjadi?</label>
                    <select name="lokasi" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none text-sm transition-all hover:border-amber-300 bg-gray-50 focus:bg-white" required>
                        <option value="" disabled selected>Pilih lokasi...</option>
                        <option value="lingkungan_sekolah">Lingkungan Sekolah</option>
                        <option value="luar_sekolah">Luar Sekolah</option>
                        <option value="dunia_maya">Dunia Maya (Internet/Sosmed)</option>
                    </select>
                </div>

                <!-- Waktu -->
                <div class="group">
                    <label class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-amber-600 transition-colors">Kapan kejadian biasanya terjadi?</label>
                    <select name="waktu" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none text-sm transition-all hover:border-amber-300 bg-gray-50 focus:bg-white" required>
                        <option value="" disabled selected>Pilih waktu...</option>
                        <option value="jam_pelajaran">Saat Jam Pelajaran</option>
                        <option value="istirahat">Saat Jam Istirahat</option>
                        <option value="pulang_sekolah">Saat/Setelah Pulang Sekolah</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Pertanyaan Kondisional (Ya/Tidak) -->
        <div class="bg-white p-7 rounded-3xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-lg hover:border-amber-100">
            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold">2</span>
                <h2 class="text-xl font-bold text-gray-800">Informasi Tambahan</h2>
            </div>
            
            <div class="space-y-6">
                <!-- Berulang -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <p class="text-sm font-semibold text-gray-700">Apakah kejadian ini terjadi berulang kali?</p>
                    <div class="flex gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="berulang" value="ya" class="peer sr-only" required>
                            <div class="py-2 px-5 rounded-xl border border-gray-200 text-sm font-medium peer-checked:bg-amber-50 peer-checked:border-amber-500 peer-checked:text-amber-700 hover:bg-gray-50 transition-all">Ya</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="berulang" value="tidak" class="peer sr-only">
                            <div class="py-2 px-5 rounded-xl border border-gray-200 text-sm font-medium peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 hover:bg-gray-50 transition-all">Tidak</div>
                        </label>
                    </div>
                </div>

                <!-- Rasa Tidak Aman -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <p class="text-sm font-semibold text-gray-700">Apakah kejadian ini membuatmu merasa tidak aman?</p>
                    <div class="flex gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="rasa_tidak_aman" value="ya" class="peer sr-only" required>
                            <div class="py-2 px-5 rounded-xl border border-gray-200 text-sm font-medium peer-checked:bg-amber-50 peer-checked:border-amber-500 peer-checked:text-amber-700 hover:bg-gray-50 transition-all">Ya</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="rasa_tidak_aman" value="tidak" class="peer sr-only">
                            <div class="py-2 px-5 rounded-xl border border-gray-200 text-sm font-medium peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 hover:bg-gray-50 transition-all">Tidak</div>
                        </label>
                    </div>
                </div>

                <!-- Saksi -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <p class="text-sm font-semibold text-gray-700">Apakah ada orang lain yang menyaksikan?</p>
                    <div class="flex gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="saksi" value="ada" class="peer sr-only" required>
                            <div class="py-2 px-5 rounded-xl border border-gray-200 text-sm font-medium peer-checked:bg-amber-50 peer-checked:border-amber-500 peer-checked:text-amber-700 hover:bg-gray-50 transition-all">Ada</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="saksi" value="tidak_ada" class="peer sr-only">
                            <div class="py-2 px-5 rounded-xl border border-gray-200 text-sm font-medium peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 hover:bg-gray-50 transition-all">Tidak ada</div>
                        </label>
                    </div>
                </div>

                <!-- Prioritas (Dibutuhkan oleh Controller) -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-gray-50">
                    <p class="text-sm font-semibold text-gray-700">Tingkat keparahan/urgensi laporan ini menurutmu?</p>
                    <div class="flex gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="prioritas" value="rendah" class="peer sr-only" required>
                            <div class="py-2 px-4 rounded-xl border border-gray-200 text-xs font-bold peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 hover:bg-gray-50 transition-all">Biasa</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="prioritas" value="sedang" class="peer sr-only">
                            <div class="py-2 px-4 rounded-xl border border-gray-200 text-xs font-bold peer-checked:bg-orange-50 peer-checked:border-orange-500 peer-checked:text-orange-700 hover:bg-gray-50 transition-all">Sedang</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="prioritas" value="tinggi" class="peer sr-only">
                            <div class="py-2 px-4 rounded-xl border border-gray-200 text-xs font-bold peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 hover:bg-gray-50 transition-all">Mendesak</div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Narasi / Cerita Bebas -->
        <div class="bg-white p-7 rounded-3xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-lg hover:border-amber-100 group">
            <div class="flex items-center gap-3 mb-2">
                <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold">3</span>
                <label for="komentar" class="block text-xl font-bold text-gray-800 group-focus-within:text-amber-600 transition-colors">Ceritakan Detail Kejadian</label>
            </div>
            <p class="text-sm text-gray-500 mb-5 ml-11">Ceritakan sedetail mungkin (siapa yang terlibat, apa yang terjadi). Sistem cerdas kami akan membantu menganalisis laporanmu agar sekolah dapat mengambil tindakan yang paling tepat.</p>
            
            <textarea name="komentar" id="komentar" rows="5" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white outline-none transition-all text-sm resize-none" placeholder="Ketik laporanmu di sini (minimal 10 karakter)..." required minlength="10"></textarea>
        </div>

        <!-- Anonimitas & Tombol Kirim -->
        <div class="bg-gray-50 p-6 sm:p-8 rounded-3xl border border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-inner">
            <div class="flex items-start flex-1">
                <div class="flex items-center h-5 mt-1">
                    <input id="anonim" name="anonim" type="checkbox" value="1" class="w-5 h-5 text-amber-500 bg-white border-gray-300 rounded focus:ring-amber-500 focus:ring-2 cursor-pointer transition-all">
                </div>
                <div class="ml-4">
                    <label for="anonim" class="font-bold text-gray-800 cursor-pointer">Sembunyikan identitas saya</label>
                    <p class="text-gray-500 text-sm mt-1 leading-relaxed">Centang opsi ini jika kamu ingin nama kamu disamarkan dari notifikasi awal guru BK.</p>
                </div>
            </div>
            
            <button type="submit" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 px-10 rounded-2xl transition-all duration-300 shadow-lg shadow-amber-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-amber-200 whitespace-nowrap active:scale-[0.98]">
                Kirim Laporan
            </button>
        </div>
    </form>
</div>
@endsection