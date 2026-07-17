@extends('layouts.app')

@section('title', 'Detail Risiko Siswa - SahabatKelas')

@section('content')
    @php
        $kategoriRisiko = $analisisTerbaru?->kategori_resiko ?? 'belum';

        $temaRisiko = match ($kategoriRisiko) {
            'tinggi' => [
                'label' => 'Risiko Tinggi',
                'badge' => 'bg-red-100 text-red-700 border-red-200',
                'panel' => 'bg-red-50 border-red-200',
                'text' => 'text-red-700',
                'bar' => 'bg-red-500',
            ],

            'sedang' => [
                'label' => 'Risiko Sedang',
                'badge' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                'panel' => 'bg-yellow-50 border-yellow-200',
                'text' => 'text-yellow-700',
                'bar' => 'bg-yellow-500',
            ],

            'rendah' => [
                'label' => 'Risiko Rendah',
                'badge' => 'bg-green-100 text-green-700 border-green-200',
                'panel' => 'bg-green-50 border-green-200',
                'text' => 'text-green-700',
                'bar' => 'bg-green-500',
            ],

            default => [
                'label' => 'Belum Dianalisis',
                'badge' => 'bg-gray-100 text-gray-600 border-gray-200',
                'panel' => 'bg-gray-50 border-gray-200',
                'text' => 'text-gray-600',
                'bar' => 'bg-gray-400',
            ],
        };

        $skorKomponen = [
            [
                'label' => 'Check-in',
                'nilai' => $analisisTerbaru?->skor_checkin,
                'warna' => 'bg-blue-500',
            ],
            [
                'label' => 'Safe Report',
                'nilai' => $analisisTerbaru?->skor_safe_report,
                'warna' => 'bg-orange-500',
            ],
            [
                'label' => 'Observasi',
                'nilai' => $analisisTerbaru?->skor_observasi,
                'warna' => 'bg-purple-500',
            ],
            [
                'label' => 'Analisis NLP',
                'nilai' => $analisisTerbaru?->skor_nlp,
                'warna' => 'bg-teal-500',
            ],
        ];

        /*
         * Laporan anonim tidak ditampilkan dalam halaman detail siswa
         * agar identitas pelapor tetap terlindungi.
         */
        $laporanTerbuka = $siswa->safeReports->filter(fn($laporan) => !$laporan->anonim);
    @endphp

    <div class="max-w-7xl mx-auto mb-10 space-y-6">

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                <div class="flex items-start gap-4">
                    <div
                        class="w-14 h-14 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-lg shrink-0">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($siswa->nama, 0, 2)) }}
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl font-bold text-gray-800">
                                {{ $siswa->nama }}
                            </h1>

                            <span class="text-xs font-semibold px-3 py-1 rounded-full border {{ $temaRisiko['badge'] }}">
                                {{ $temaRisiko['label'] }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-500 mt-1">
                            NIS {{ $siswa->nis ?? '-' }}
                            ·
                            {{ $siswa->kelas->nama_kelas ?? 'Kelas belum ditentukan' }}
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            Status siswa:
                            <span class="font-medium text-gray-600">
                                {{ ucfirst($siswa->status ?? '-') }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('guru.heatmap') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                        Kembali ke Heatmap
                    </a>

                    <a href="{{ route('guru.tindak-lanjut.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-teal-200 text-sm font-medium text-teal-700 hover:bg-teal-50 transition-colors">
                        Daftar Tindak Lanjut
                    </a>

                    <a href="{{ route('guru.observasi.create', $siswa->id_siswa) }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold hover:bg-teal-700 transition-colors">
                        Tambah Observasi
                    </a>
                </div>
            </div>
        </div>

        {{-- Ringkasan risiko --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Skor akhir --}}
            <div class="rounded-2xl border p-6 {{ $temaRisiko['panel'] }}">
                <p class="text-sm font-medium text-gray-600">
                    Skor Risiko Terbaru
                </p>

                @if ($analisisTerbaru)
                    <div class="flex items-end gap-2 mt-3">
                        <span class="text-5xl font-bold {{ $temaRisiko['text'] }}">
                            {{ number_format($analisisTerbaru->skor_akhir, 1) }}
                        </span>

                        <span class="text-sm text-gray-400 mb-1">
                            / 100
                        </span>
                    </div>

                    <div class="w-full h-2.5 bg-white/80 rounded-full overflow-hidden mt-5">
                        <div class="h-full rounded-full {{ $temaRisiko['bar'] }}"
                            style="width: {{ min((float) $analisisTerbaru->skor_akhir, 100) }}%"></div>
                    </div>

                    <p class="text-xs text-gray-500 mt-3">
                        Dianalisis pada
                        {{ \Carbon\Carbon::parse($analisisTerbaru->tanggal_analisis)->translatedFormat('d F Y, H:i') }}
                    </p>
                @else
                    <p class="text-lg font-semibold text-gray-500 mt-4">
                        Belum tersedia
                    </p>

                    <p class="text-xs text-gray-400 mt-2">
                        Siswa belum mempunyai hasil analisis risiko.
                    </p>
                @endif
            </div>

            {{-- Identitas siswa --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-semibold text-gray-800 mb-4">
                    Identitas Siswa
                </h2>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">Nama</dt>
                        <dd class="font-medium text-gray-800 text-right">
                            {{ $siswa->nama }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">NIS</dt>
                        <dd class="font-medium text-gray-800">
                            {{ $siswa->nis ?? '-' }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">Jenis kelamin</dt>
                        <dd class="font-medium text-gray-800">
                            {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">Kelas</dt>
                        <dd class="font-medium text-gray-800">
                            {{ $siswa->kelas->nama_kelas ?? '-' }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">Tahun ajaran</dt>
                        <dd class="font-medium text-gray-800">
                            {{ $siswa->kelas->tahun_ajaran ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Ringkasan sumber data --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-semibold text-gray-800 mb-4">
                    Sumber Pemantauan
                </h2>

                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-blue-700">
                            {{ $siswa->safe_reports_count }}
                        </p>
                        <p class="text-xs text-blue-600 mt-1">
                            Check-in
                        </p>
                    </div>

                    <div class="bg-orange-50 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-orange-700">
                            {{ $siswa->observasi_count }}
                        </p>
                        <p class="text-xs text-orange-600 mt-1">
                            Laporan
                        </p>
                    </div>

                    <div class="bg-purple-50 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold text-purple-700">
                            {{ $siswa->observasi->count() }}
                        </p>
                        <p class="text-xs text-purple-600 mt-1">
                            Observasi
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Skor komponen --}}
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="mb-5">
                <h2 class="text-lg font-bold text-gray-800">
                    Komponen Skor Risiko
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Rincian nilai dari setiap sumber data pada analisis terbaru.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($skorKomponen as $komponen)
                    <div class="border border-gray-100 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-600">
                                {{ $komponen['label'] }}
                            </p>

                            <span class="w-2.5 h-2.5 rounded-full {{ $komponen['warna'] }}"></span>
                        </div>

                        @if ($komponen['nilai'] !== null)
                            <p class="text-2xl font-bold text-gray-800 mt-2">
                                {{ number_format((float) $komponen['nilai'], 1) }}
                            </p>

                            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden mt-3">
                                <div class="h-full rounded-full {{ $komponen['warna'] }}"
                                    style="width: {{ min((float) $komponen['nilai'], 100) }}%"></div>
                            </div>
                        @else
                            <p class="text-sm font-semibold text-gray-400 mt-3">
                                Belum tersedia
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>

            @if (
                $analisisTerbaru &&
                    $analisisTerbaru->skor_akhir !== null &&
                    $analisisTerbaru->skor_checkin === null &&
                    $analisisTerbaru->skor_safe_report === null &&
                    $analisisTerbaru->skor_observasi === null &&
                    $analisisTerbaru->skor_nlp === null)
                <div class="mt-5 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <p class="text-sm text-yellow-700">
                        Skor akhir sudah tersedia, tetapi rincian skor komponennya belum tersimpan.
                        Perhitungan risiko perlu diperiksa kembali agar hasil lebih transparan.
                    </p>
                </div>
            @endif
        </section>

        {{-- Analisis NLP dan rekomendasi --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- Hasil NLP --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">
                        Hasil Analisis NLP
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Analisis teks yang terhubung dengan hasil risiko terbaru.
                    </p>
                </div>

                <div class="p-6">
                    @if ($analisisTerbaru && $analisisTerbaru->hasilNlp->isNotEmpty())
                        <div class="space-y-4">
                            @foreach ($analisisTerbaru->hasilNlp as $hasil)
                                <article class="border border-gray-100 rounded-xl p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <span
                                            class="text-xs font-semibold px-2.5 py-1 rounded-full bg-teal-50 text-teal-700 border border-teal-100">
                                            {{ $hasil->sumber_data === 'safe_report' ? 'Safe Report' : 'Check-in' }}
                                        </span>

                                        <span class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($hasil->updated_at)->translatedFormat('d M Y, H:i') }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 mt-4">
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-500">
                                                Skor NLP
                                            </p>

                                            <p class="text-sm font-semibold text-gray-800 mt-1">
                                                {{ $hasil->skor_nlp !== null ? number_format((float) $hasil->skor_nlp, 1) . '/100' : '-' }}
                                            </p>
                                        </div>

                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-500">
                                                Intensitas
                                            </p>

                                            <p class="text-sm font-semibold text-gray-800 mt-1">
                                                {{ ucfirst($hasil->intensitas ?? '-') }}
                                            </p>
                                        </div>

                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-500">
                                                Indikasi perundungan
                                            </p>

                                            <p
                                                class="text-sm font-semibold mt-1
                                            {{ $hasil->indikasi_perundungan === 'ya' ? 'text-red-600' : 'text-green-600' }}">
                                                {{ ucfirst($hasil->indikasi_perundungan ?? '-') }}
                                            </p>
                                        </div>

                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-xs text-gray-500">
                                                Probabilitas perundungan
                                            </p>

                                            <p class="text-sm font-semibold text-gray-800 mt-1">
                                                {{ $hasil->confidence_indikasi !== null ? number_format((float) $hasil->confidence_indikasi, 1) . '%' : '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($hasil->hasil_ringkasan)
                                        <div class="mt-4">
                                            <p class="text-xs font-medium text-gray-500 mb-1">
                                                Ringkasan
                                            </p>

                                            <p class="text-sm text-gray-700 leading-relaxed">
                                                {{ $hasil->hasil_ringkasan }}
                                            </p>
                                        </div>
                                    @endif

                                    @if ($hasil->kata_kunci)
                                        <div class="mt-3">
                                            <p class="text-xs text-gray-500">
                                                Kata kunci:
                                                <span class="font-medium text-gray-700">
                                                    {{ $hasil->kata_kunci }}
                                                </span>
                                            </p>
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="py-10 text-center">
                            <p class="text-sm font-medium text-gray-500">
                                Hasil NLP belum tersedia.
                            </p>

                            <p class="text-xs text-gray-400 mt-1">
                                Pastikan layanan NLP dan queue worker sedang berjalan.
                            </p>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Rekomendasi --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">
                        Rekomendasi Pendampingan
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Saran tindak lanjut berdasarkan analisis terbaru.
                    </p>
                </div>

                <div class="p-6">
                    @if ($analisisTerbaru && $analisisTerbaru->rekomendasi->isNotEmpty())
                        <div class="space-y-4">
                            @foreach ($analisisTerbaru->rekomendasi as $rekomendasi)
                                @php
                                    $warnaPrioritas = match ($rekomendasi->prioritas) {
                                        'tinggi' => 'bg-red-100 text-red-700 border-red-200',
                                        'sedang' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                        default => 'bg-green-100 text-green-700 border-green-200',
                                    };
                                @endphp

                                <article class="border border-gray-100 rounded-xl p-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-800">
                                                {{ $rekomendasi->jenis_rekomendasi }}
                                            </h3>

                                            <p class="text-sm text-gray-600 leading-relaxed mt-2">
                                                {{ $rekomendasi->deskripsi }}
                                            </p>
                                        </div>

                                        <span
                                            class="text-xs font-semibold px-2.5 py-1 rounded-full border shrink-0 {{ $warnaPrioritas }}">
                                            {{ ucfirst($rekomendasi->prioritas) }}
                                        </span>
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                            <p class="text-xs text-gray-500">
                                                Status:
                                                <span class="font-medium text-gray-700">
                                                    {{ ucfirst($rekomendasi->status) }}
                                                </span>
                                            </p>

                                            @if ($rekomendasi->status === 'menunggu')
                                                <a href="{{ route('guru.tindak-lanjut.create', $rekomendasi->id_rekomendasi) }}"
                                                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-teal-600 text-white text-xs font-semibold hover:bg-teal-700">
                                                    Buat Tindak Lanjut
                                                </a>
                                            @else
                                                <span
                                                    class="inline-flex px-3 py-1.5 rounded-lg bg-green-50 text-green-700 text-xs font-semibold">
                                                    Sudah diterapkan
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="py-10 text-center">
                            <p class="text-sm font-medium text-gray-500">
                                Belum ada rekomendasi pendampingan.
                            </p>

                            <p class="text-xs text-gray-400 mt-1">
                                Rekomendasi akan muncul setelah aturan rekomendasi dijalankan.
                            </p>
                        </div>
                    @endif
                </div>
            </section>
        </div>

        {{-- Riwayat check-in --}}
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">
                    Riwayat Check-in
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Lima check-in terbaru yang diisi siswa.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                            <th class="px-6 py-3 font-medium">Perasaan</th>
                            <th class="px-6 py-3 font-medium">Rasa aman</th>
                            <th class="px-6 py-3 font-medium">Gangguan teman</th>
                            <th class="px-6 py-3 font-medium">Ingin dibantu</th>
                            <th class="px-6 py-3 font-medium">Komentar</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($siswa->checkIns as $checkIn)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    {{ \Carbon\Carbon::parse($checkIn->tanggal)->translatedFormat('d M Y') }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ \Illuminate\Support\Str::headline($checkIn->perasaan) }}
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    {{ $checkIn->rasa_aman }}/4
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    {{ $checkIn->gangguan_teman }}/4
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs font-medium px-2.5 py-1 rounded-full
                                    {{ $checkIn->ingin_dibantu === 'ya_mendesak'
                                        ? 'bg-red-100 text-red-700'
                                        : ($checkIn->ingin_dibantu === 'ya'
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : 'bg-gray-100 text-gray-600') }}">
                                        {{ \Illuminate\Support\Str::headline($checkIn->ingin_dibantu) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-gray-600 min-w-72">
                                    {{ $checkIn->komentar ?: 'Tidak ada komentar.' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    Belum ada data check-in.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Riwayat Safe Report --}}
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">
                    Riwayat Safe Report
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Hanya laporan non-anonim yang ditampilkan pada halaman siswa.
                </p>
            </div>

            <div class="p-6">
                @forelse ($laporanTerbuka as $laporan)
                    @php
                        $warnaPrioritasLaporan = match ($laporan->prioritas) {
                            'tinggi' => 'bg-red-100 text-red-700 border-red-200',
                            'sedang' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                            default => 'bg-green-100 text-green-700 border-green-200',
                        };
                    @endphp

                    <article class="border border-gray-100 rounded-xl p-5 mb-4 last:mb-0">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="font-semibold text-gray-800">
                                        {{ \Illuminate\Support\Str::headline($laporan->jenis) }}
                                    </h3>

                                    <span
                                        class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $warnaPrioritasLaporan }}">
                                        Prioritas {{ ucfirst($laporan->prioritas) }}
                                    </span>
                                </div>

                                <p class="text-xs text-gray-500 mt-2">
                                    {{ \Illuminate\Support\Str::headline($laporan->lokasi) }}
                                    ·
                                    {{ \Illuminate\Support\Str::headline($laporan->waktu) }}
                                    ·
                                    {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>

                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-600">
                                {{ ucfirst($laporan->status) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-700 leading-relaxed mt-4">
                            {{ $laporan->komentar }}
                        </p>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Pelapor</p>
                                <p class="text-sm font-medium text-gray-800 mt-1">
                                    {{ ucfirst($laporan->pelapor) }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Berulang</p>
                                <p class="text-sm font-medium text-gray-800 mt-1">
                                    {{ ucfirst($laporan->berulang) }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Merasa tidak aman</p>
                                <p class="text-sm font-medium text-gray-800 mt-1">
                                    {{ ucfirst($laporan->rasa_tidak_aman) }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Saksi</p>
                                <p class="text-sm font-medium text-gray-800 mt-1">
                                    {{ ucfirst($laporan->saksi) }}
                                </p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="py-10 text-center">
                        <p class="text-sm text-gray-500">
                            Tidak ada Safe Report non-anonim yang dapat ditampilkan.
                        </p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Riwayat observasi --}}
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div
                class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        Riwayat Observasi Guru
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Lima observasi terbaru terhadap kondisi siswa.
                    </p>
                </div>

                <a href="{{ route('guru.observasi.create', $siswa->id_siswa) }}"
                    class="text-sm font-semibold text-teal-600 hover:text-teal-700">
                    Tambah Observasi
                </a>
            </div>

            <div class="p-6">
                @forelse ($siswa->observasi as $observasi)
                    <article class="border border-gray-100 rounded-xl p-5 mb-4 last:mb-0">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800">
                                    Observasi oleh {{ $observasi->guru->nama ?? 'Guru' }}
                                </h3>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($observasi->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            <span
                                class="text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $observasi->perlu_tindak_lanjut === 'ya' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $observasi->perlu_tindak_lanjut === 'ya' ? 'Perlu Tindak Lanjut' : 'Belum Mendesak' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mt-4">
                            @foreach ([
            'Perubahan' => $observasi->perubahan_perilaku,
            'Interaksi' => $observasi->interaksi,
            'Kenyamanan' => $observasi->kenyamanan,
            'Isolasi' => $observasi->isolasi,
            'Tekanan' => $observasi->tekanan,
            'Agresif' => $observasi->agresif,
        ] as $label => $nilai)
                                <div class="bg-gray-50 rounded-lg p-3 text-center">
                                    <p class="text-lg font-bold text-gray-800">
                                        {{ $nilai }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $label }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        @if ($observasi->catatan)
                            <div class="mt-4 bg-gray-50 rounded-xl p-4">
                                <p class="text-xs font-medium text-gray-500 mb-1">
                                    Catatan guru
                                </p>

                                <p class="text-sm text-gray-700 leading-relaxed">
                                    {{ $observasi->catatan }}
                                </p>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="py-10 text-center">
                        <p class="text-sm text-gray-500">
                            Belum ada observasi guru untuk siswa ini.
                        </p>

                        <a href="{{ route('guru.observasi.create', $siswa->id_siswa) }}"
                            class="inline-block mt-3 text-sm font-semibold text-teal-600 hover:text-teal-700">
                            Tambahkan observasi pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Riwayat analisis --}}
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">
                    Riwayat Analisis Risiko
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Perubahan skor risiko siswa dari waktu ke waktu.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                            <th class="px-6 py-3 font-medium">Skor akhir</th>
                            <th class="px-6 py-3 font-medium">Kategori</th>
                            <th class="px-6 py-3 font-medium">Check-in</th>
                            <th class="px-6 py-3 font-medium">Laporan</th>
                            <th class="px-6 py-3 font-medium">Observasi</th>
                            <th class="px-6 py-3 font-medium">NLP</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($siswa->analisisResiko as $analisis)
                            @php
                                $badgeAnalisis = match ($analisis->kategori_resiko) {
                                    'tinggi' => 'bg-red-100 text-red-700',
                                    'sedang' => 'bg-yellow-100 text-yellow-700',
                                    default => 'bg-green-100 text-green-700',
                                };
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    {{ \Carbon\Carbon::parse($analisis->tanggal_analisis)->translatedFormat('d M Y, H:i') }}
                                </td>

                                <td class="px-6 py-4 font-bold text-gray-800">
                                    {{ number_format((float) $analisis->skor_akhir, 1) }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badgeAnalisis }}">
                                        {{ ucfirst($analisis->kategori_resiko) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $analisis->skor_checkin !== null ? number_format((float) $analisis->skor_checkin, 1) : '-' }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $analisis->skor_safe_report !== null ? number_format((float) $analisis->skor_safe_report, 1) : '-' }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $analisis->skor_observasi !== null ? number_format((float) $analisis->skor_observasi, 1) : '-' }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $analisis->skor_nlp !== null ? number_format((float) $analisis->skor_nlp, 1) : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                    Belum ada riwayat analisis risiko.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </div>
@endsection
