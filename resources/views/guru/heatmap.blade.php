@extends('layouts.app')

@section('title', 'Heatmap Risiko Kelas - SahabatKelas')

@section('content')
<div class="max-w-7xl mx-auto mb-10 space-y-6">

    {{-- Header halaman --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Heatmap Risiko Kelas
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Pemetaan tingkat risiko siswa berdasarkan hasil analisis terbaru.
                </p>
            </div>

            <div class="flex items-center gap-2 text-xs text-gray-500">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                    Rendah
                </span>

                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                    Sedang
                </span>

                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                    Tinggi
                </span>

                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-gray-300"></span>
                    Belum dianalisis
                </span>
            </div>
        </div>
    </div>

    {{-- Ringkasan statistik --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-medium text-gray-500">
                Jumlah Kelas
            </p>

            <p class="text-2xl font-bold text-gray-800 mt-1">
                {{ $ringkasan['jumlah_kelas'] }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-medium text-gray-500">
                Jumlah Siswa
            </p>

            <p class="text-2xl font-bold text-gray-800 mt-1">
                {{ $ringkasan['jumlah_siswa'] }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-red-100 border-l-4 border-l-red-500">
            <p class="text-sm font-medium text-gray-500">
                Risiko Tinggi
            </p>

            <p class="text-2xl font-bold text-red-600 mt-1">
                {{ $ringkasan['risiko_tinggi'] }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-gray-400">
            <p class="text-sm font-medium text-gray-500">
                Belum Dianalisis
            </p>

            <p class="text-2xl font-bold text-gray-700 mt-1">
                {{ $ringkasan['belum_dianalisis'] }}
            </p>
        </div>
    </div>

    {{-- Cakupan analisis keseluruhan --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <div>
                <p class="font-semibold text-gray-800">
                    Cakupan Analisis Siswa
                </p>

                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $ringkasan['jumlah_dianalisis'] }}
                    dari
                    {{ $ringkasan['jumlah_siswa'] }}
                    siswa sudah memiliki hasil analisis.
                </p>
            </div>

            <span class="text-sm font-bold text-teal-700">
                {{ number_format($ringkasan['cakupan_analisis'], 1) }}%
            </span>
        </div>

        <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
            <div
                class="h-full bg-teal-500 rounded-full"
                style="width: {{ min($ringkasan['cakupan_analisis'], 100) }}%"
            ></div>
        </div>
    </div>

    {{-- Daftar heatmap per kelas --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        @forelse ($dataKelas as $kelas)

            @php
                $temaKelas = match ($kelas->kategori_kelas) {
                    'tinggi' => [
                        'border' => 'border-red-200',
                        'header' => 'bg-red-50',
                        'badge' => 'bg-red-100 text-red-700 border-red-200',
                        'label' => 'Perlu Perhatian',
                    ],

                    'sedang' => [
                        'border' => 'border-yellow-200',
                        'header' => 'bg-yellow-50',
                        'badge' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        'label' => 'Perlu Dipantau',
                    ],

                    'rendah' => [
                        'border' => 'border-green-200',
                        'header' => 'bg-green-50',
                        'badge' => 'bg-green-100 text-green-700 border-green-200',
                        'label' => 'Risiko Rendah',
                    ],

                    default => [
                        'border' => 'border-gray-200',
                        'header' => 'bg-gray-50',
                        'badge' => 'bg-gray-100 text-gray-600 border-gray-200',
                        'label' => 'Belum Dianalisis',
                    ],
                };
            @endphp

            <section class="bg-white rounded-2xl shadow-sm border {{ $temaKelas['border'] }} overflow-hidden">

                {{-- Header kelas --}}
                <div class="p-5 {{ $temaKelas['header'] }} border-b {{ $temaKelas['border'] }}">
                    <div class="flex items-start justify-between gap-4">

                        <div>
                            <h2 class="text-lg font-bold text-gray-800">
                                {{ $kelas->nama_kelas }}
                            </h2>

                            <p class="text-xs text-gray-500 mt-1">
                                {{ $kelas->jurusan ?? 'Jurusan belum ditentukan' }}
                                ·
                                Tahun Ajaran {{ $kelas->tahun_ajaran ?? '-' }}
                            </p>
                        </div>

                        <span class="text-xs font-semibold px-3 py-1.5 rounded-full border {{ $temaKelas['badge'] }}">
                            {{ $temaKelas['label'] }}
                        </span>
                    </div>
                </div>

                <div class="p-5 space-y-5">

                    {{-- Ringkasan kelas --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

                        <div class="rounded-xl bg-gray-50 p-3 text-center">
                            <p class="text-xl font-bold text-gray-800">
                                {{ $kelas->jumlah_siswa }}
                            </p>

                            <p class="text-xs text-gray-500">
                                Siswa
                            </p>
                        </div>

                        <div class="rounded-xl bg-green-50 p-3 text-center">
                            <p class="text-xl font-bold text-green-700">
                                {{ $kelas->risiko_rendah }}
                            </p>

                            <p class="text-xs text-green-600">
                                Rendah
                            </p>
                        </div>

                        <div class="rounded-xl bg-yellow-50 p-3 text-center">
                            <p class="text-xl font-bold text-yellow-700">
                                {{ $kelas->risiko_sedang }}
                            </p>

                            <p class="text-xs text-yellow-600">
                                Sedang
                            </p>
                        </div>

                        <div class="rounded-xl bg-red-50 p-3 text-center">
                            <p class="text-xl font-bold text-red-700">
                                {{ $kelas->risiko_tinggi }}
                            </p>

                            <p class="text-xs text-red-600">
                                Tinggi
                            </p>
                        </div>
                    </div>

                    {{-- Indeks dan cakupan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div class="border border-gray-100 rounded-xl p-4">
                            <p class="text-xs text-gray-500">
                                Indeks Risiko Kelas
                            </p>

                            @if ($kelas->indeks_risiko !== null)
                                <p class="text-2xl font-bold text-gray-800 mt-1">
                                    {{ number_format($kelas->indeks_risiko, 1) }}
                                    <span class="text-sm font-normal text-gray-400">
                                        / 100
                                    </span>
                                </p>
                            @else
                                <p class="text-sm font-semibold text-gray-400 mt-2">
                                    Belum tersedia
                                </p>
                            @endif
                        </div>

                        <div class="border border-gray-100 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-xs text-gray-500">
                                    Cakupan Analisis
                                </p>

                                <p class="text-xs font-bold text-teal-700">
                                    {{ number_format($kelas->cakupan_analisis, 1) }}%
                                </p>
                            </div>

                            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden mt-3">
                                <div
                                    class="h-full bg-teal-500 rounded-full"
                                    style="width: {{ min($kelas->cakupan_analisis, 100) }}%"
                                ></div>
                            </div>

                            <p class="text-xs text-gray-400 mt-2">
                                {{ $kelas->jumlah_dianalisis }}
                                dari
                                {{ $kelas->jumlah_siswa }}
                                siswa
                            </p>
                        </div>
                    </div>

                    {{-- Heatmap siswa --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800">
                                    Peta Risiko Siswa
                                </h3>

                                <p class="text-xs text-gray-500 mt-0.5">
                                    Klik kotak siswa untuk membuka detail.
                                </p>
                            </div>

                            @if ($kelas->belum_dianalisis > 0)
                                <span class="text-xs text-gray-500">
                                    {{ $kelas->belum_dianalisis }}
                                    belum dianalisis
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 xl:grid-cols-6 gap-2">

                            @foreach ($kelas->siswa as $siswa)

                                @php
                                    $kategoriSiswa =
                                        $siswa->analisisTerbaru?->kategori_resiko
                                        ?? 'belum';

                                    $warnaSiswa = match ($kategoriSiswa) {
                                        'tinggi' =>
                                            'bg-red-500 hover:bg-red-600 text-white border-red-600',

                                        'sedang' =>
                                            'bg-yellow-400 hover:bg-yellow-500 text-yellow-950 border-yellow-500',

                                        'rendah' =>
                                            'bg-green-500 hover:bg-green-600 text-white border-green-600',

                                        default =>
                                            'bg-gray-200 hover:bg-gray-300 text-gray-600 border-gray-300',
                                    };

                                    $inisial = \Illuminate\Support\Str::upper(
                                        \Illuminate\Support\Str::substr(
                                            $siswa->nama,
                                            0,
                                            2
                                        )
                                    );

                                    $skorSiswa =
                                        $siswa->analisisTerbaru?->skor_akhir;
                                @endphp

                                <a
                                    href="{{ route('guru.siswa.detail', $siswa->id_siswa) }}"
                                    class="aspect-square rounded-xl border flex flex-col items-center justify-center transition-all hover:-translate-y-0.5 hover:shadow-md {{ $warnaSiswa }}"
                                    title="{{ $siswa->nama }} — {{ $kategoriSiswa === 'belum' ? 'Belum dianalisis' : 'Risiko ' . ucfirst($kategoriSiswa) . ', skor ' . number_format($skorSiswa, 1) }}"
                                >
                                    <span class="text-sm font-bold">
                                        {{ $inisial }}
                                    </span>

                                    @if ($skorSiswa !== null)
                                        <span class="text-[10px] opacity-90">
                                            {{ number_format($skorSiswa, 0) }}
                                        </span>
                                    @else
                                        <span class="text-[10px]">
                                            –
                                        </span>
                                    @endif
                                </a>

                            @endforeach
                        </div>

                        @if ($kelas->siswa->isEmpty())
                            <div class="py-8 text-center rounded-xl bg-gray-50 border border-dashed border-gray-200">
                                <p class="text-sm text-gray-500">
                                    Belum ada siswa aktif pada kelas ini.
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Daftar siswa prioritas --}}
                    @if ($kelas->risiko_tinggi > 0)
                        <div class="border-t border-gray-100 pt-4">
                            <h3 class="text-sm font-semibold text-red-700 mb-3">
                                Siswa Prioritas Pendampingan
                            </h3>

                            <div class="space-y-2">
                                @foreach (
                                    $kelas->siswa->filter(
                                        fn ($siswa) =>
                                            $siswa->analisisTerbaru?->kategori_resiko
                                            === 'tinggi'
                                    )
                                    as $siswa
                                )
                                    <a
                                        href="{{ route('guru.siswa.detail', $siswa->id_siswa) }}"
                                        class="flex items-center justify-between gap-4 p-3 rounded-xl bg-red-50 border border-red-100 hover:bg-red-100 transition-colors"
                                    >
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">
                                                {{ $siswa->nama }}
                                            </p>

                                            <p class="text-xs text-gray-500">
                                                NIS {{ $siswa->nis ?? '-' }}
                                            </p>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm font-bold text-red-700">
                                                {{ number_format($siswa->analisisTerbaru->skor_akhir, 1) }}
                                            </p>

                                            <p class="text-xs text-red-600">
                                                Lihat detail
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </section>

        @empty

            <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 p-12 text-center">
                <h2 class="text-lg font-semibold text-gray-700">
                    Data kelas belum tersedia
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Tambahkan data kelas dan siswa terlebih dahulu.
                </p>
            </div>

        @endforelse
    </div>

</div>
@endsection