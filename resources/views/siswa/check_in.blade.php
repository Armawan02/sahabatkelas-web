@extends('layouts.app')

@section('title', 'Check-in Mingguan - SahabatKelas')

@section('content')
<div class="max-w-3xl mx-auto mb-10">
    
    <!-- 1. Bagian Pembuka -->
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mb-6">
        <h1 class="text-2xl font-bold text-blue-800 mb-2">Check-in Mingguan</h1>
        <p class="text-blue-700 text-sm mb-4 leading-relaxed">
            Ceritakan kondisi kamu selama satu minggu terakhir. Jawaban digunakan untuk membantu guru memberikan pendampingan yang sesuai.
        </p>
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 text-xs">
            <span class="bg-white text-blue-600 px-3 py-1.5 rounded-lg font-medium shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Periode: {{ $periode }}
            </span>
            <span class="text-blue-500 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Bersifat terbatas & rahasia
            </span>
        </div>
    </div>

    <form action="{{ route('siswa.checkin.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- 2. Kondisi Perasaan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Bagaimana perasaanmu selama satu minggu terakhir?</h2>
            <div class="grid grid-cols-5 gap-2 sm:gap-4 text-center">
                <label class="cursor-pointer">
                    <input type="radio" name="perasaan" value="sangat_baik" class="peer sr-only" required>
                    <div class="peer-checked:bg-green-100 peer-checked:ring-2 peer-checked:ring-green-500 rounded-xl p-3 hover:bg-gray-50 transition">
                        <div class="text-3xl mb-1">😄</div>
                        <div class="text-xs text-gray-600">Sangat baik</div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="perasaan" value="baik" class="peer sr-only">
                    <div class="peer-checked:bg-teal-100 peer-checked:ring-2 peer-checked:ring-teal-500 rounded-xl p-3 hover:bg-gray-50 transition">
                        <div class="text-3xl mb-1">🙂</div>
                        <div class="text-xs text-gray-600">Baik</div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="perasaan" value="biasa_saja" class="peer sr-only">
                    <div class="peer-checked:bg-yellow-100 peer-checked:ring-2 peer-checked:ring-yellow-500 rounded-xl p-3 hover:bg-gray-50 transition">
                        <div class="text-3xl mb-1">😐</div>
                        <div class="text-xs text-gray-600">Biasa saja</div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="perasaan" value="kurang_baik" class="peer sr-only">
                    <div class="peer-checked:bg-orange-100 peer-checked:ring-2 peer-checked:ring-orange-500 rounded-xl p-3 hover:bg-gray-50 transition">
                        <div class="text-3xl mb-1">🙁</div>
                        <div class="text-xs text-gray-600">Kurang baik</div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="perasaan" value="sangat_tidak_baik" class="peer sr-only">
                    <div class="peer-checked:bg-red-100 peer-checked:ring-2 peer-checked:ring-red-500 rounded-xl p-3 hover:bg-gray-50 transition">
                        <div class="text-3xl mb-1">😢</div>
                        <div class="text-xs text-gray-600">Sangat tidak baik</div>
                    </div>
                </label>
            </div>
        </div>

        <!-- 3. Pertanyaan Utama -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Kondisi Lingkungan</h2>
            <p class="text-sm text-gray-500 mb-6">Pilih salah satu skala untuk setiap pernyataan di bawah ini.</p>
            
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

            <div class="space-y-6">
                @foreach($pertanyaan as $name => $teks)
                <div class="border-b border-gray-50 pb-5">
                    <p class="text-sm font-medium text-gray-800 mb-3">{{ $teks }}</p>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                        @foreach($skala as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="{{ $name }}" value="{{ $val }}" class="peer sr-only" required>
                            <div class="text-center py-2 px-1 rounded-lg border border-gray-200 text-xs text-gray-600 peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 peer-checked:font-medium hover:bg-gray-50 transition">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <!-- Pertanyaan Melihat Bullying (Ya/Tidak) -->
                <div>
                    <p class="text-sm font-medium text-gray-800 mb-3">Saya melihat teman lain mengalami perlakuan tidak menyenangkan.</p>
                    <div class="flex gap-4">
                        <label class="cursor-pointer w-32">
                            <input type="radio" name="melihat_bullying" value="ya" class="peer sr-only" required>
                            <div class="text-center py-2 rounded-lg border border-gray-200 text-sm peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 hover:bg-gray-50">Ya</div>
                        </label>
                        <label class="cursor-pointer w-32">
                            <input type="radio" name="melihat_bullying" value="tidak" class="peer sr-only">
                            <div class="text-center py-2 rounded-lg border border-gray-200 text-sm peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 hover:bg-gray-50">Tidak</div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Pertanyaan Dukungan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Sistem Dukungan</h2>
            <p class="text-sm font-medium text-gray-700 mb-3">Apakah kamu memiliki seseorang yang dapat dipercaya untuk bercerita tentang masalahmu?</p>
            <div class="flex flex-wrap gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="teman_diskusi" value="ada" class="peer sr-only" required>
                    <div class="py-2 px-4 rounded-full border border-gray-200 text-sm peer-checked:bg-teal-50 peer-checked:border-teal-500 peer-checked:text-teal-700 hover:bg-gray-50">Ya, saya punya</div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="teman_diskusi" value="tidak_ada" class="peer sr-only">
                    <div class="py-2 px-4 rounded-full border border-gray-200 text-sm peer-checked:bg-teal-50 peer-checked:border-teal-500 peer-checked:text-teal-700 hover:bg-gray-50">Belum memiliki / Tidak ingin menjawab</div>
                </label>
            </div>
        </div>

        <!-- 5. Catatan Tambahan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <label for="komentar" class="block text-lg font-semibold text-gray-800 mb-1">Ada hal lain yang ingin kamu sampaikan? (Opsional)</label>
            <p class="text-sm text-gray-500 mb-3">Ceritakan secara singkat apabila kamu merasa perlu.</p>
            <textarea name="komentar" id="komentar" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors text-sm" placeholder="Ketik di sini..."></textarea>
        </div>

        <!-- 6. Pilihan Tindak Lanjut -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Permintaan Pendampingan</h2>
            <p class="text-sm font-medium text-gray-700 mb-3">Apakah kamu ingin berbicara dengan guru BK atau wali kelas mengenai kondisimu saat ini?</p>
            <div class="flex flex-col sm:flex-row gap-3">
                <label class="cursor-pointer flex-1">
                    <input type="radio" name="ingin_dibantu" value="ya_mendesak" class="peer sr-only" required>
                    <div class="text-center py-3 px-2 rounded-xl border border-gray-200 text-sm peer-checked:bg-orange-50 peer-checked:border-orange-400 peer-checked:text-orange-800 hover:bg-gray-50 transition">Ya, secepatnya</div>
                </label>
                <label class="cursor-pointer flex-1">
                    <input type="radio" name="ingin_dibantu" value="ya_biasa" class="peer sr-only">
                    <div class="text-center py-3 px-2 rounded-xl border border-gray-200 text-sm peer-checked:bg-blue-50 peer-checked:border-blue-400 peer-checked:text-blue-800 hover:bg-gray-50 transition">Ya, tapi tidak mendesak</div>
                </label>
                <label class="cursor-pointer flex-1">
                    <input type="radio" name="ingin_dibantu" value="tidak" class="peer sr-only">
                    <div class="text-center py-3 px-2 rounded-xl border border-gray-200 text-sm peer-checked:bg-gray-100 peer-checked:border-gray-400 peer-checked:text-gray-700 hover:bg-gray-50 transition">Belum / Tidak ingin</div>
                </label>
            </div>
        </div>

        <!-- 7. Tombol Pengiriman -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50 p-6 rounded-2xl border border-gray-100">
            <p class="text-xs text-gray-500 flex-1">Pastikan jawaban yang kamu berikan sesuai dengan apa yang kamu rasakan minggu ini.</p>
            <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-xl transition-colors shadow-sm text-sm">
                Kirim Check-in
            </button>
        </div>
    </form>
</div>
@endsection