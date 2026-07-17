@extends('layouts.app')

@section('title', 'Detail Tindak Lanjut - SahabatKelas')

@section('content')
    @php
        $warnaStatus =
            $tindakLanjut->status === 'selesai'
                ? 'bg-green-100 text-green-700 border-green-200'
                : 'bg-yellow-100 text-yellow-700 border-yellow-200';

        $warnaRisiko = match ($analisis->kategori_resiko) {
            'tinggi' => 'bg-red-100 text-red-700 border-red-200',

            'sedang' => 'bg-yellow-100 text-yellow-700 border-yellow-200',

            default => 'bg-green-100 text-green-700 border-green-200',
        };
    @endphp

    <div class="max-w-5xl mx-auto mb-10 space-y-6">

        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <a href="{{ route('guru.tindak-lanjut.index') }}"
                    class="text-sm font-medium text-teal-600 hover:text-teal-700">
                    ← Kembali ke daftar tindak lanjut
                </a>

                <div class="flex flex-wrap items-center gap-3 mt-3">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Detail Tindak Lanjut
                    </h1>

                    <span class="inline-flex px-3 py-1 rounded-full border text-xs font-semibold {{ $warnaStatus }}">
                        {{ $tindakLanjut->status === 'selesai' ? 'Selesai' : 'Sedang Diproses' }}
                    </span>
                </div>

                <p class="text-sm text-gray-500 mt-1">
                    Informasi pelaksanaan pendampingan siswa.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('guru.siswa.detail', $siswa->id_siswa) }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Lihat Detail Siswa
                </a>

                @if ($tindakLanjut->status === 'proses')
                    <a href="{{ route('guru.monitoring.create', $tindakLanjut->id_tindak_lanjut) }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                        Tambah Monitoring
                    </a>
                @endif

                <a href="{{ route('guru.tindak-lanjut.edit', $tindakLanjut->id_tindak_lanjut) }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold hover:bg-teal-700">
                    Perbarui Tindak Lanjut
                </a>
            </div>
        </div>

        {{-- Informasi utama --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Siswa --}}
            <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-bold text-gray-800">
                    Informasi Siswa
                </h2>

                <dl class="space-y-3 mt-5 text-sm">
                    <div>
                        <dt class="text-gray-500">
                            Nama
                        </dt>

                        <dd class="font-semibold text-gray-800 mt-1">
                            {{ $siswa->nama }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">
                            NIS
                        </dt>

                        <dd class="font-medium text-gray-800 mt-1">
                            {{ $siswa->nis ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">
                            Kelas
                        </dt>

                        <dd class="font-medium text-gray-800 mt-1">
                            {{ $siswa->kelas?->nama_kelas ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </section>

            {{-- Risiko --}}
            <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-bold text-gray-800">
                    Dasar Analisis
                </h2>

                <div class="mt-5">
                    <span class="inline-flex px-3 py-1 rounded-full border text-xs font-semibold {{ $warnaRisiko }}">
                        Risiko
                        {{ ucfirst($analisis->kategori_resiko) }}
                    </span>

                    <p class="text-4xl font-bold text-gray-800 mt-4">
                        {{ number_format((float) $analisis->skor_akhir, 1) }}
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Skor risiko dari 100
                    </p>

                    <p class="text-xs text-gray-400 mt-4">
                        Analisis:
                        {{ \Carbon\Carbon::parse($analisis->tanggal_analisis)->translatedFormat('d F Y, H:i') }}
                    </p>
                </div>
            </section>

            {{-- Guru --}}
            <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-bold text-gray-800">
                    Penanggung Jawab
                </h2>

                <dl class="space-y-3 mt-5 text-sm">
                    <div>
                        <dt class="text-gray-500">
                            Nama guru
                        </dt>

                        <dd class="font-semibold text-gray-800 mt-1">
                            {{ $tindakLanjut->guru?->nama ?? 'Guru' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">
                            Jabatan
                        </dt>

                        <dd class="font-medium text-gray-800 mt-1">
                            {{ $tindakLanjut->guru?->jabatan ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">
                            Tanggal pelaksanaan
                        </dt>

                        <dd class="font-medium text-gray-800 mt-1">
                            {{ \Carbon\Carbon::parse($tindakLanjut->tanggal)->translatedFormat('d F Y') }}
                        </dd>
                    </div>
                </dl>
            </section>
        </div>

        {{-- Informasi tindakan --}}
        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="border-b border-gray-100 pb-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-teal-600">
                    Jenis tindakan
                </p>

                <h2 class="text-xl font-bold text-gray-800 mt-2">
                    {{ $tindakLanjut->jenis_tindakan }}
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">
                        Rencana dan Catatan
                    </h3>

                    <div class="bg-gray-50 rounded-xl p-4 mt-3">
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $tindakLanjut->catatan }}
                        </p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-800">
                        Hasil Pelaksanaan
                    </h3>

                    <div class="bg-gray-50 rounded-xl p-4 mt-3">
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $tindakLanjut->hasil }}</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Timeline monitoring --}}
        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div
                class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        Monitoring Intervensi
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Riwayat perkembangan siswa setelah tindak lanjut dilakukan.
                    </p>
                </div>

                @if ($tindakLanjut->status === 'proses')
                    <a href="{{ route('guru.monitoring.create', $tindakLanjut->id_tindak_lanjut) }}"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                        + Tambah Monitoring
                    </a>
                @endif
            </div>

            <div class="p-6">
                @forelse ($daftarMonitoring as $monitoring)
                    @php
                        $temaEvaluasi = match ($monitoring->hasil_evaluasi) {
                            'membaik' => [
                                'badge' => 'bg-green-100 text-green-700 border-green-200',

                                'dot' => 'bg-green-500',

                                'label' => 'Kondisi Membaik',
                            ],

                            'memburuk' => [
                                'badge' => 'bg-red-100 text-red-700 border-red-200',

                                'dot' => 'bg-red-500',

                                'label' => 'Kondisi Memburuk',
                            ],

                            default => [
                                'badge' => 'bg-yellow-100 text-yellow-700 border-yellow-200',

                                'dot' => 'bg-yellow-500',

                                'label' => 'Kondisi Tetap',
                            ],
                        };

                        $labelKeputusan = match ($monitoring->tindakan_berikutnya) {
                            'lanjut_monitoring' => 'Lanjutkan Monitoring',

                            'tindakan_tambahan' => 'Buat Tindakan Tambahan',

                            'rujuk' => 'Rujuk ke Pihak Lain',

                            'selesai' => 'Tindak Lanjut Diselesaikan',

                            default => '-',
                        };
                    @endphp

                    <article class="relative pl-8 pb-8 last:pb-0">

                        {{-- Garis timeline --}}
                        @if (!$loop->last)
                            <div class="absolute left-2.5 top-5 bottom-0 w-px bg-gray-200"></div>
                        @endif

                        {{-- Titik timeline --}}
                        <div
                            class="absolute left-0 top-1 w-5 h-5 rounded-full border-4 border-white shadow {{ $temaEvaluasi['dot'] }}">
                        </div>

                        <div class="border border-gray-100 rounded-xl p-5">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ \Carbon\Carbon::parse($monitoring->tanggal_monitoring)->translatedFormat('d F Y') }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        Dicatat oleh
                                        {{ $monitoring->guru?->nama ?? 'Guru' }}
                                    </p>
                                </div>

                                <span
                                    class="inline-flex px-3 py-1 rounded-full border text-xs font-semibold {{ $temaEvaluasi['badge'] }}">
                                    {{ $temaEvaluasi['label'] }}
                                </span>
                            </div>

                            {{-- Indikator --}}
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 mt-5">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">
                                        Perasaan Aman
                                    </p>

                                    <p class="text-lg font-bold text-gray-800 mt-1">
                                        {{ $monitoring->perasaan_aman ?? '-' }}
                                        @if ($monitoring->perasaan_aman)
                                            <span class="text-xs font-normal text-gray-400">
                                                /4
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">
                                        Interaksi Sosial
                                    </p>

                                    <p class="text-lg font-bold text-gray-800 mt-1">
                                        {{ $monitoring->interaksi_sosial ?? '-' }}
                                        @if ($monitoring->interaksi_sosial)
                                            <span class="text-xs font-normal text-gray-400">
                                                /4
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">
                                        Keterlibatan Belajar
                                    </p>

                                    <p class="text-lg font-bold text-gray-800 mt-1">
                                        {{ $monitoring->keterlibatan_belajar ?? '-' }}
                                        @if ($monitoring->keterlibatan_belajar)
                                            <span class="text-xs font-normal text-gray-400">
                                                /4
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">
                                        Risiko Saat Monitoring
                                    </p>

                                    <p class="text-lg font-bold text-gray-800 mt-1">
                                        {{ $monitoring->skor_risiko !== null ? number_format((float) $monitoring->skor_risiko, 1) : '-' }}
                                    </p>

                                    @if ($monitoring->kategori_risiko)
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ ucfirst($monitoring->kategori_risiko) }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            @if ($monitoring->catatan_siswa)
                                <div class="mt-4 bg-blue-50 rounded-xl p-4">
                                    <p class="text-xs font-semibold text-blue-700">
                                        Keterangan Siswa
                                    </p>

                                    <p class="text-sm text-blue-900 leading-relaxed mt-1 whitespace-pre-line">
                                        {{ $monitoring->catatan_siswa }}</p>
                                </div>
                            @endif

                            <div class="mt-4">
                                <p class="text-xs font-semibold text-gray-500">
                                    Catatan Guru
                                </p>

                                <p class="text-sm text-gray-700 leading-relaxed mt-1 whitespace-pre-line">
                                    {{ $monitoring->catatan_guru }}</p>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-xs text-gray-500">
                                    Keputusan berikutnya:
                                    <span class="font-semibold text-gray-700">
                                        {{ $labelKeputusan }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="py-10 text-center">
                        <p class="text-sm font-medium text-gray-500">
                            Belum ada monitoring intervensi.
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            Monitoring dapat ditambahkan setelah tindakan pendampingan dilaksanakan.
                        </p>

                        @if ($tindakLanjut->status === 'proses')
                            <a href="{{ route('guru.monitoring.create', $tindakLanjut->id_tindak_lanjut) }}"
                                class="inline-flex mt-4 px-4 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                                Tambah Monitoring Pertama
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Rekomendasi terkait --}}
        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">
                    Rekomendasi Terkait
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Rekomendasi yang diterapkan pada analisis risiko ini.
                </p>
            </div>

            <div class="p-6">
                @forelse ($rekomendasiTerkait as $rekomendasi)
                    @php
                        $warnaPrioritas = match ($rekomendasi->prioritas) {
                            'tinggi' => 'bg-red-100 text-red-700 border-red-200',

                            'sedang' => 'bg-yellow-100 text-yellow-700 border-yellow-200',

                            default => 'bg-green-100 text-green-700 border-green-200',
                        };
                    @endphp

                    <article class="border border-gray-100 rounded-xl p-4 mb-4 last:mb-0">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <h3 class="font-semibold text-gray-800">
                                    {{ $rekomendasi->jenis_rekomendasi }}
                                </h3>

                                <p class="text-sm text-gray-600 leading-relaxed mt-2">
                                    {{ $rekomendasi->deskripsi }}
                                </p>
                            </div>

                            <span
                                class="inline-flex px-2.5 py-1 rounded-full border text-xs font-semibold shrink-0 {{ $warnaPrioritas }}">
                                {{ ucfirst($rekomendasi->prioritas) }}
                            </span>
                        </div>
                    </article>
                @empty
                    <div class="py-8 text-center">
                        <p class="text-sm text-gray-500">
                            Tidak ada rekomendasi diterapkan yang ditemukan.
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            Tindak lanjut tetap dapat dilihat melalui catatan pelaksanaan.
                        </p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
