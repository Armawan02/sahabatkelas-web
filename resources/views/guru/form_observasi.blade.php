@extends('layouts.app')

@section('title', 'Observasi Siswa - SahabatKelas')

@section('content')
<div class="max-w-4xl mx-auto mb-10">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Lembar Observasi Guru</h1>
            <p class="text-gray-500 text-sm mt-1">Siswa: <span class="font-semibold text-teal-600">{{ $siswa->nama }} ({{ $siswa->kelas->nama_kelas ?? '-' }})</span></p>
        </div>
        <a href="{{ route('guru.siswa.detail', $siswa->id_siswa) }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium underline">Batal & Kembali</a>
    </div>

    <form action="{{ route('guru.observasi.store', $siswa->id_siswa) }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-2 border-b border-gray-100 pb-2">Penilaian Parameter (Skala 0 - 4)</h2>
            <p class="text-sm text-gray-500 mb-6">Pilih 0 jika tidak ada indikasi, dan 4 jika indikasi sangat terlihat/ekstrem.</p>

            @php
                $indikator = [
                    'perubahan_perilaku' => 'Perubahan Perilaku Mendadak (Murung, diam, menarik diri)',
                    'interaksi' => 'Penurunan Kualitas Interaksi Sosial dengan Teman',
                    'kenyamanan' => 'Penurunan Minat/Kenyamanan Belajar di Kelas',
                    'isolasi' => 'Indikasi Isolasi Sosial (Sering menyendiri saat istirahat)',
                    'tekanan' => 'Tanda-tanda Tekanan Emosional (Mudah menangis, cemas)',
                    'agresif' => 'Perilaku Agresif atau Tanda Fisik Kelelahan/Cedera',
                ];
                $skala = [0, 1, 2, 3, 4];
            @endphp

            <div class="space-y-5">
                @foreach($indikator as $name => $label)
                <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-50 pb-4">
                    <label class="text-sm font-medium text-gray-700 mb-3 md:mb-0 w-full md:w-1/2">{{ $label }}</label>
                    <div class="flex gap-2 w-full md:w-auto">
                        @foreach($skala as $val)
                        <label class="cursor-pointer flex-1 md:flex-none">
                            <input type="radio" name="{{ $name }}" value="{{ $val }}" class="peer sr-only" required>
                            <div class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-sm peer-checked:bg-teal-50 peer-checked:border-teal-500 peer-checked:text-teal-700 hover:bg-gray-50 transition-colors">
                                {{ $val }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Catatan Tambahan</h2>
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Apakah siswa ini memerlukan tindak lanjut segera?</label>
                <div class="flex gap-4">
                    <label class="cursor-pointer w-32">
                        <input type="radio" name="perlu_tindak_lanjut" value="ya" class="peer sr-only" required>
                        <div class="text-center py-2 rounded-lg border border-gray-200 text-sm peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 font-medium">Ya, Mendesak</div>
                    </label>
                    <label class="cursor-pointer w-32">
                        <input type="radio" name="perlu_tindak_lanjut" value="tidak" class="peer sr-only">
                        <div class="text-center py-2 rounded-lg border border-gray-200 text-sm peer-checked:bg-teal-50 peer-checked:border-teal-500 peer-checked:text-teal-700 font-medium">Belum Perlu</div>
                    </label>
                </div>
            </div>

            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Detail Observasi (Opsional)</label>
                <textarea name="catatan" id="catatan" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-colors text-sm" placeholder="Tuliskan catatan spesifik yang Anda lihat pada siswa ini..."></textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-8 rounded-xl transition-colors shadow-sm text-sm">
                Simpan Observasi & Hitung Risiko
            </button>
        </div>
    </form>
</div>
@endsection