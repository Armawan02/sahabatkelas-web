@extends('layouts.app')

@section('title', 'Check-in Mingguan - SahabatKelas')

@section('content')
<div class="max-w-3xl mx-auto mb-10">
    
    <!-- 1. Bagian Pembuka -->
    <div class="bg-gradient-to-r from-blue-600 to-teal-500 rounded-3xl p-8 mb-8 text-white shadow-xl shadow-blue-200/50 relative overflow-hidden transition-all duration-300 hover:shadow-2xl">
        <!-- Dekorasi -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-48 h-48 bg-white opacity-20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-20 -mb-10 w-32 h-32 bg-teal-300 opacity-30 rounded-full blur-2xl"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-md text-white rounded-2xl flex items-center justify-center border border-white/30 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black tracking-tight">Check-in Harian</h1>
                </div>
                <p class="text-blue-50 text-base leading-relaxed max-w-2xl relative z-10 mb-5">
                    Ceritakan kondisi kamu selama satu hari terakhir. Jawaban digunakan untuk membantu guru memberikan pendampingan yang sesuai.
                </p>
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 text-xs">
                    <span class="bg-white/20 backdrop-blur-md border border-white/30 text-white px-4 py-2 rounded-xl font-medium shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Periode: {{ $periode }}
                    </span>
                    <span class="bg-blue-700/50 backdrop-blur-md border border-blue-600/50 text-white px-4 py-2 rounded-xl font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Bersifat terbatas & rahasia
                    </span>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('siswa.checkin.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- 2. Kondisi Perasaan -->
        <div class="bg-white p-7 rounded-3xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-lg hover:border-blue-100">
            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">1</span>
                <h2 class="text-xl font-bold text-gray-800">Bagaimana perasaanmu hari ini?</h2>
            </div>
            
            <div class="grid grid-cols-5 gap-2 sm:gap-4 text-center">
                <label class="cursor-pointer group">
                    <input type="radio" name="perasaan" value="sangat_baik" class="peer sr-only" required>
                    <div class="peer-checked:bg-green-100 peer-checked:ring-4 peer-checked:ring-green-500/30 peer-checked:border-green-500 rounded-2xl p-4 border border-transparent group-hover:bg-gray-50 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="text-4xl mb-2 group-hover:scale-110 transition-transform duration-300">😄</div>
                        <div class="text-xs font-semibold text-gray-600 peer-checked:text-green-700">Sangat baik</div>
                    </div>
                </label>
                <label class="cursor-pointer group">
                    <input type="radio" name="perasaan" value="baik" class="peer sr-only">
                    <div class="peer-checked:bg-teal-100 peer-checked:ring-4 peer-checked:ring-teal-500/30 peer-checked:border-teal-500 rounded-2xl p-4 border border-transparent group-hover:bg-gray-50 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="text-4xl mb-2 group-hover:scale-110 transition-transform duration-300">🙂</div>
                        <div class="text-xs font-semibold text-gray-600 peer-checked:text-teal-700">Baik</div>
                    </div>
                </label>
                <label class="cursor-pointer group">
                    <input type="radio" name="perasaan" value="biasa_saja" class="peer sr-only">
                    <div class="peer-checked:bg-yellow-100 peer-checked:ring-4 peer-checked:ring-yellow-500/30 peer-checked:border-yellow-500 rounded-2xl p-4 border border-transparent group-hover:bg-gray-50 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="text-4xl mb-2 group-hover:scale-110 transition-transform duration-300">😐</div>
                        <div class="text-xs font-semibold text-gray-600 peer-checked:text-yellow-700">Biasa saja</div>
                    </div>
                </label>
                <label class="cursor-pointer group">
                    <input type="radio" name="perasaan" value="kurang_baik" class="peer sr-only">
                    <div class="peer-checked:bg-orange-100 peer-checked:ring-4 peer-checked:ring-orange-500/30 peer-checked:border-orange-500 rounded-2xl p-4 border border-transparent group-hover:bg-gray-50 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="text-4xl mb-2 group-hover:scale-110 transition-transform duration-300">🙁</div>
                        <div class="text-xs font-semibold text-gray-600 peer-checked:text-orange-700">Kurang baik</div>
                    </div>
                </label>
                <label class="cursor-pointer group">
                    <input type="radio" name="perasaan" value="sangat_tidak_baik" class="peer sr-only">
                    <div class="peer-checked:bg-red-100 peer-checked:ring-4 peer-checked:ring-red-500/30 peer-checked:border-red-500 rounded-2xl p-4 border border-transparent group-hover:bg-gray-50 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="text-4xl mb-2 group-hover:scale-110 transition-transform duration-300">😢</div>
                        <div class="text-xs font-semibold text-gray-600 peer-checked:text-red-700">Sangat buruk</div>
                    </div>
                </label>
            </div>
        </div>

        <!-- 3. Pertanyaan Utama -->
        <div class="bg-white p-7 rounded-3xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-lg hover:border-blue-100">
            <div class="flex items-center gap-3 mb-2">
                <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">2</span>
                <h2 class="text-xl font-bold text-gray-800">Kondisi Lingkungan</h2>
            </div>
            <p class="text-sm text-gray-500 mb-6 ml-11">Pilih salah satu skala untuk setiap pernyataan di bawah ini.</p>
            
            @php
                $skala = [
                    '0' => 'Tidak pernah',
                    '1' => 'Jarang',
                    '2' => 'Kadang-kadang',
                    '3' => 'Sering',
                    '4' => 'Sangat sering'
                ];
                $pertanyaan = [
                    'rasa_aman' => 'Saya merasa tidak aman ketika berada di sekolah.',
                    'gangguan_teman' => 'Saya menerima ejekan, hinaan, dorongan, atau perlakuan tidak nyaman dari teman.',
                    'diterima_teman' => 'Saya sengaja dijauhi, dikucilkan, atau tidak dilibatkan oleh teman.',
                    'kenyamanan_belajar' => 'Gangguan tersebut membuat saya sulit belajar, berkonsentrasi, atau enggan datang ke sekolah.'
                ];
            @endphp

            <div class="space-y-6 ml-0 md:ml-11">
                @foreach($pertanyaan as $name => $teks)
                <div class="border-b border-gray-50 pb-6 group">
                    <p class="text-sm font-semibold text-gray-800 mb-4 group-hover:text-blue-600 transition-colors">{{ $teks }}</p>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                        @foreach($skala as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="{{ $name }}" value="{{ $val }}" class="peer sr-only" required>
                            <div class="text-center py-2.5 px-2 rounded-xl border border-gray-200 text-xs font-medium text-gray-600 peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 hover:bg-gray-50 hover:border-blue-300 transition-all">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <!-- Pertanyaan Melihat Bullying (Ya/Tidak) -->
                <div class="group pt-2">
                    <p class="text-sm font-semibold text-gray-800 mb-4 group-hover:text-blue-600 transition-colors">Saya melihat teman lain mengalami perlakuan tidak menyenangkan.</p>
                    <div class="flex gap-4">
                        <label class="cursor-pointer w-32">
                            <input type="radio" name="melihat_bullying" value="ya" class="peer sr-only" required>
                            <div class="text-center py-2.5 rounded-xl border border-gray-200 text-sm font-medium peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 hover:bg-gray-50 hover:border-blue-300 transition-all">Ya</div>
                        </label>
                        <label class="cursor-pointer w-32">
                            <input type="radio" name="melihat_bullying" value="tidak" class="peer sr-only">
                            <div class="text-center py-2.5 rounded-xl border border-gray-200 text-sm font-medium peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 hover:bg-gray-50 hover:border-blue-300 transition-all">Tidak</div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Pertanyaan Dukungan -->
        <div class="bg-white p-7 rounded-3xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-lg hover:border-blue-100 group">
            <div class="flex items-center gap-3 mb-2">
                <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">3</span>
                <h2 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors">Sistem Dukungan</h2>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-4 ml-11">Apakah kamu memiliki seseorang yang dapat dipercaya untuk bercerita tentang masalahmu?</p>
            <div class="flex flex-wrap gap-3 ml-0 md:ml-11">
                <label class="cursor-pointer">
                    <input type="radio" name="teman_diskusi" value="ada" class="peer sr-only" required>
                    <div class="py-2.5 px-6 rounded-full border border-gray-200 text-sm font-medium peer-checked:bg-teal-50 peer-checked:border-teal-500 peer-checked:text-teal-700 hover:bg-gray-50 hover:border-teal-300 transition-all">Ya, saya punya</div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="teman_diskusi" value="tidak_ada" class="peer sr-only">
                    <div class="py-2.5 px-6 rounded-full border border-gray-200 text-sm font-medium peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 hover:bg-gray-50 hover:border-blue-300 transition-all">Belum memiliki / Tidak ingin menjawab</div>
                </label>
            </div>
        </div>

        <!-- 5. Catatan Tambahan -->
        <div class="bg-white p-7 rounded-3xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-lg hover:border-blue-100 group">
            <div class="flex items-center gap-3 mb-2">
                <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">4</span>
                <label for="komentar" class="block text-xl font-bold text-gray-800 group-focus-within:text-blue-600 transition-colors">Ada hal lain yang ingin kamu sampaikan? (Opsional)</label>
            </div>
            <p class="text-sm text-gray-500 mb-4 ml-11">Ceritakan secara singkat apabila kamu merasa perlu.</p>
            <div class="ml-0 md:ml-11">
                <textarea name="komentar" id="komentar" rows="3" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white outline-none transition-all text-sm resize-none" placeholder="Ketik di sini..."></textarea>
            </div>
        </div>

        <!-- 6. Pilihan Tindak Lanjut -->
        <div class="bg-white p-7 rounded-3xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-lg hover:border-blue-100 group">
            <div class="flex items-center gap-3 mb-2">
                <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">5</span>
                <h2 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors">Permintaan Pendampingan</h2>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-4 ml-11">Apakah kamu ingin berbicara dengan guru BK atau wali kelas mengenai kondisimu saat ini?</p>
            <div class="flex flex-col sm:flex-row gap-4 ml-0 md:ml-11">
                <label class="cursor-pointer flex-1">
                    <input type="radio" name="ingin_dibantu" value="ya_mendesak" class="peer sr-only" required>
                    <div class="text-center py-4 px-2 rounded-2xl border border-gray-200 text-sm font-bold peer-checked:bg-orange-50 peer-checked:border-orange-400 peer-checked:text-orange-800 hover:bg-gray-50 hover:border-orange-300 transition-all">Ya, secepatnya</div>
                </label>
                <label class="cursor-pointer flex-1">
                    <input type="radio" name="ingin_dibantu" value="ya_biasa" class="peer sr-only">
                    <div class="text-center py-4 px-2 rounded-2xl border border-gray-200 text-sm font-bold peer-checked:bg-blue-50 peer-checked:border-blue-400 peer-checked:text-blue-800 hover:bg-gray-50 hover:border-blue-300 transition-all">Ya, tapi tidak mendesak</div>
                </label>
                <label class="cursor-pointer flex-1">
                    <input type="radio" name="ingin_dibantu" value="tidak" class="peer sr-only">
                    <div class="text-center py-4 px-2 rounded-2xl border border-gray-200 text-sm font-bold peer-checked:bg-gray-100 peer-checked:border-gray-400 peer-checked:text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all">Belum / Tidak ingin</div>
                </label>
            </div>
        </div>

        <!-- 7. Tombol Pengiriman -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 bg-gray-50 p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-inner">
            <p class="text-sm text-gray-500 flex-1 leading-relaxed font-medium">Pastikan jawaban yang kamu berikan sesuai dengan apa yang kamu rasakan minggu ini.</p>
            <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-10 rounded-2xl transition-all duration-300 shadow-lg shadow-blue-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-200 active:scale-[0.98]">
                Kirim Check-in
            </button>
        </div>
    </form>
</div>
@endsection