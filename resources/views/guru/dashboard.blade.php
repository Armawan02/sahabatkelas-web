@extends('layouts.app')

@section('title', 'Dashboard Guru - SahabatKelas')

@section('content')
<div class="max-w-7xl mx-auto mb-10 space-y-6">

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <section
        class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100
               flex flex-col md:flex-row md:items-center md:justify-between gap-4"
    >
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Selamat datang,
                {{ $guru?->nama
                    ?? auth()->user()?->email
                    ?? 'Guru' }}
            </h1>

            <p class="text-gray-500 text-sm mt-1">
                {{ $guru?->jabatan ?? 'Guru Pendamping' }}
                · Pantau kondisi dan tingkat risiko siswa.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a
                href="{{ route('guru.heatmap') }}"
                class="inline-flex items-center justify-center px-4 py-2.5
                       rounded-xl border border-teal-200 text-sm font-semibold
                       text-teal-700 hover:bg-teal-50 transition-colors"
            >
                Lihat Heatmap
            </a>

            <a
                href="{{ route('guru.tindak-lanjut.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5
                       rounded-xl bg-teal-600 text-sm font-semibold text-white
                       hover:bg-teal-700 transition-colors"
            >
                Tindak Lanjut
            </a>
        </div>
    </section>

    {{-- Statistik --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- Risiko Tinggi --}}
        <a
            href="{{ route('guru.heatmap') }}"
            class="bg-white p-5 rounded-2xl shadow-sm border border-red-100
                   border-l-4 border-l-red-500 flex items-center gap-4
                   hover:shadow-md transition-shadow"
        >
            <div
                class="w-12 h-12 bg-red-50 text-red-500 rounded-full
                       flex items-center justify-center shrink-0"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856
                           c1.54 0 2.502-1.667 1.732-3L13.732
                           4c-.77-1.333-2.694-1.333-3.464
                           0L3.34 16c-.77 1.333.192 3
                           1.732 3z"
                    />
                </svg>
            </div>

            <div>
                <p class="text-sm text-gray-500 font-medium">
                    Siswa Risiko Tinggi
                </p>

                <h2 class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $totalRisikoTinggi ?? 0 }}

                    <span class="text-sm font-normal text-gray-500">
                        orang
                    </span>
                </h2>
            </div>
        </a>

        {{-- Laporan Menunggu --}}
        <div
            class="bg-white p-5 rounded-2xl shadow-sm border border-orange-100
                   border-l-4 border-l-orange-500 flex items-center gap-4"
        >
            <div
                class="w-12 h-12 bg-orange-50 text-orange-500 rounded-full
                       flex items-center justify-center shrink-0"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0
                           002.22 0L21 8M5 19h14a2 2
                           0 002-2V7a2 2 0 00-2-2H5
                           a2 2 0 00-2 2v10a2 2 0
                           002 2z"
                    />
                </svg>
            </div>

            <div>
                <p class="text-sm text-gray-500 font-medium">
                    Safe Report Menunggu
                </p>

                <h2 class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $totalLaporanBaru ?? 0 }}

                    <span class="text-sm font-normal text-gray-500">
                        laporan
                    </span>
                </h2>
            </div>
        </div>

        {{-- Check-in --}}
        <div
            class="bg-white p-5 rounded-2xl shadow-sm border border-blue-100
                   border-l-4 border-l-blue-500 flex items-center gap-4"
        >
            <div
                class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full
                       flex items-center justify-center shrink-0"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9
                           0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
            </div>

            <div>
                <p class="text-sm text-gray-500 font-medium">
                    Check-in Minggu Ini
                </p>

                <h2 class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $totalCheckInMingguIni ?? 0 }}

                    <span class="text-sm font-normal text-gray-500">
                        dari {{ $totalSiswa ?? 0 }} siswa
                    </span>
                </h2>
            </div>
        </div>

        {{-- Tindak Lanjut --}}
        <a
            href="{{ route('guru.tindak-lanjut.index') }}"
            class="bg-white p-5 rounded-2xl shadow-sm border border-teal-100
                   border-l-4 border-l-teal-500 flex items-center gap-4
                   hover:shadow-md transition-shadow"
        >
            <div
                class="w-12 h-12 bg-teal-50 text-teal-600 rounded-full
                       flex items-center justify-center shrink-0"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12
                           a2 2 0 002 2h10a2 2 0
                           002-2V7a2 2 0 00-2-2h-2
                           M9 5a2 2 0 002 2h2a2 2
                           0 002-2M9 5a2 2 0 012-2
                           h2a2 2 0 012 2"
                    />
                </svg>
            </div>

            <div>
                <p class="text-sm text-gray-500 font-medium">
                    Tindak Lanjut Aktif
                </p>

                <h2 class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $totalTindakLanjutAktif ?? 0 }}

                    <span class="text-sm font-normal text-gray-500">
                        kasus
                    </span>
                </h2>
            </div>
        </a>
    </section>

    {{-- Tabel utama --}}
    <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Prioritas pendampingan --}}
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100
                   overflow-hidden"
        >
            <div
                class="px-6 py-4 border-b border-gray-100 bg-gray-50
                       flex flex-col sm:flex-row sm:items-center
                       sm:justify-between gap-3"
            >
                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        Prioritas Pendampingan
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        Siswa dengan analisis risiko terbaru berkategori tinggi.
                    </p>
                </div>

                <span
                    class="inline-flex w-fit text-xs font-medium bg-red-100
                           text-red-700 px-2.5 py-1 rounded-full"
                >
                    Butuh Tindakan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-white text-gray-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">
                                Siswa
                            </th>

                            <th class="px-6 py-3 font-medium">
                                Skor Risiko
                            </th>

                            <th class="px-6 py-3 font-medium text-right">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($siswaRisikoTinggi as $siswa)
                            @php
                                $analisis = $siswa->analisisTerbaru;
                            @endphp

                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800">
                                        {{ $siswa->nama
                                            ?? 'Siswa Tidak Diketahui' }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $siswa->kelas?->nama_kelas
                                            ?? 'Kelas -' }}
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5
                                               bg-red-50 text-red-700 font-bold
                                               px-2.5 py-1 rounded-lg
                                               border border-red-100"
                                    >
                                        <svg
                                            class="w-3.5 h-3.5"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                clip-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0
                                                   8 8 0 0116 0zm-7 4a1
                                                   1 0 11-2 0 1 1 0 012
                                                   0zm-1-9a1 1 0 00-1
                                                   1v4a1 1 0 102 0V6a1
                                                   1 0 00-1-1z"
                                            />
                                        </svg>

                                        {{ $analisis?->skor_akhir !== null
                                            ? number_format(
                                                (float) $analisis->skor_akhir,
                                                1
                                            )
                                            : '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <a
                                        href="{{ route(
                                            'guru.siswa.detail',
                                            $siswa->id_siswa
                                        ) }}"
                                        class="inline-flex text-teal-600
                                               hover:text-teal-800 font-medium
                                               text-xs bg-teal-50
                                               hover:bg-teal-100 px-3 py-1.5
                                               rounded-md transition-colors"
                                    >
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="3"
                                    class="px-6 py-10 text-center"
                                >
                                    <p class="text-sm font-medium text-gray-500">
                                        Belum ada siswa berisiko tinggi.
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Kondisi siswa akan muncul setelah data dianalisis.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 text-right">
                <a
                    href="{{ route('guru.heatmap') }}"
                    class="text-sm font-semibold text-teal-600
                           hover:text-teal-700"
                >
                    Lihat seluruh heatmap →
                </a>
            </div>
        </div>

        {{-- Laporan terbaru --}}
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100
                   overflow-hidden"
        >
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">
                    Laporan Masuk Terbaru
                </h2>

                <p class="text-xs text-gray-500 mt-1">
                    Ringkasan Safe Report yang terakhir dikirim siswa.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-white text-gray-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">
                                Pelapor / Waktu
                            </th>

                            <th class="px-6 py-3 font-medium">
                                Prioritas
                            </th>

                            <th class="px-6 py-3 font-medium text-right">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($laporanTerbaru as $laporan)
                            @php
                                $prioritas = strtolower(
                                    (string) ($laporan->prioritas ?? '')
                                );

                                $temaPrioritas = match ($prioritas) {
                                    'tinggi' => [
                                        'label' => 'Tinggi',
                                        'class' =>
                                            'bg-red-50 text-red-700 border-red-100',
                                    ],

                                    'sedang' => [
                                        'label' => 'Sedang',
                                        'class' =>
                                            'bg-orange-50 text-orange-700 border-orange-100',
                                    ],

                                    'rendah' => [
                                        'label' => 'Rendah',
                                        'class' =>
                                            'bg-green-50 text-green-700 border-green-100',
                                    ],

                                    default => [
                                        'label' => 'Belum Dinilai',
                                        'class' =>
                                            'bg-gray-50 text-gray-600 border-gray-100',
                                    ],
                                };

                                $statusLaporan = strtolower(
                                    (string) ($laporan->status ?? '')
                                );

                                $temaStatus = match ($statusLaporan) {
                                    'menunggu' => [
                                        'label' => 'Menunggu Review',
                                        'class' =>
                                            'bg-orange-50 text-orange-700 border-orange-100',
                                    ],

                                    'diproses', 'proses' => [
                                        'label' => 'Sedang Diproses',
                                        'class' =>
                                            'bg-blue-50 text-blue-700 border-blue-100',
                                    ],

                                    'selesai' => [
                                        'label' => 'Selesai',
                                        'class' =>
                                            'bg-green-50 text-green-700 border-green-100',
                                    ],

                                    default => [
                                        'label' => 'Belum Diproses',
                                        'class' =>
                                            'bg-gray-50 text-gray-600 border-gray-100',
                                    ],
                                };
                            @endphp

                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800">
                                        {{ $laporan->anonim
                                            ? 'Siswa (Anonim)'
                                            : (
                                                $laporan->siswa?->nama
                                                ?? 'Siswa'
                                            ) }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $laporan->created_at
                                            ? $laporan->created_at
                                                ->diffForHumans()
                                            : '-' }}
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-lg
                                               border text-xs font-semibold
                                               {{ $temaPrioritas['class'] }}"
                                    >
                                        {{ $temaPrioritas['label'] }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-lg
                                               border text-xs font-semibold
                                               {{ $temaStatus['class'] }}"
                                    >
                                        {{ $temaStatus['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="3"
                                    class="px-6 py-10 text-center"
                                >
                                    <p class="text-sm font-medium text-gray-500">
                                        Belum ada laporan terbaru.
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Safe Report siswa akan ditampilkan di sini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection