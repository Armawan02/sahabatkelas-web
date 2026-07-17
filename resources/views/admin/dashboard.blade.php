@extends('layouts.app')

@section('title', 'Dashboard Admin - SahabatKelas')

@section('content')
@php
    $admin = auth()->user();

    $namaAdmin = $admin?->name
        ?? $admin?->email
        ?? 'Administrator';

    $totalPeran = array_sum($distribusiPeran);

    $persentaseAdmin = $totalPeran > 0
        ? ($distribusiPeran['admin'] / $totalPeran) * 100
        : 0;

    $persentaseGuru = $totalPeran > 0
        ? ($distribusiPeran['guru'] / $totalPeran) * 100
        : 0;

    $persentaseSiswa = $totalPeran > 0
        ? ($distribusiPeran['siswa'] / $totalPeran) * 100
        : 0;
@endphp

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
        class="bg-gradient-to-r from-slate-700 to-slate-800
               rounded-2xl p-6 md:p-8 text-white shadow-sm
               relative overflow-hidden"
    >
        <div
            class="absolute -top-12 -right-12 w-48 h-48
                   rounded-full bg-white/10 blur-2xl"
        ></div>

        <div
            class="relative z-10 flex flex-col md:flex-row
                   md:items-center md:justify-between gap-5"
        >
            <div>
                <p class="text-sm font-medium text-slate-300">
                    Panel Administrator
                </p>

                <h1 class="text-2xl md:text-3xl font-bold mt-1">
                    Selamat datang, {{ $namaAdmin }}
                </h1>

                <p class="text-sm text-slate-300 mt-2 max-w-2xl">
                    Kelola data pengguna, kelas, dan pantau aktivitas
                    operasional platform SAHABATKELAS.
                </p>
            </div>

            <div
                class="inline-flex w-fit items-center gap-2
                       rounded-xl border border-white/20
                       bg-white/10 px-4 py-2.5"
            >
                <span class="relative flex h-2.5 w-2.5">
                    <span
                        class="absolute inline-flex h-full w-full
                               animate-ping rounded-full bg-green-300
                               opacity-75"
                    ></span>

                    <span
                        class="relative inline-flex h-2.5 w-2.5
                               rounded-full bg-green-400"
                    ></span>
                </span>

                <span class="text-sm font-medium">
                    Sistem aktif
                </span>
            </div>
        </div>
    </section>

    {{-- Statistik utama --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- Pengguna --}}
        <div
            class="bg-white rounded-2xl border border-violet-100
                   border-l-4 border-l-violet-500 shadow-sm p-5
                   flex items-center gap-4"
        >
            <div
                class="w-12 h-12 rounded-full bg-violet-50
                       text-violet-600 flex items-center justify-center
                       shrink-0"
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
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857
                           M17 20H7m10 0v-2c0-.656-.126-1.283
                           -.356-1.857M7 20H2v-2a3 3 0
                           015.356-1.857M7 20v-2c0-.656.126
                           -1.283.356-1.857m0 0a5.002 5.002
                           0 019.288 0M15 7a3 3 0 11-6
                           0 3 3 0 016 0zm6 3a2 2 0
                           11-4 0 2 2 0 014 0zM7 10a2
                           2 0 11-4 0 2 2 0 014 0z"
                    />
                </svg>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">
                    Total Pengguna
                </p>

                <p class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $ringkasan['total_pengguna'] ?? 0 }}

                    <span class="text-sm font-normal text-gray-500">
                        akun
                    </span>
                </p>
            </div>
        </div>

        {{-- Siswa --}}
        <div
            class="bg-white rounded-2xl border border-blue-100
                   border-l-4 border-l-blue-500 shadow-sm p-5
                   flex items-center gap-4"
        >
            <div
                class="w-12 h-12 rounded-full bg-blue-50
                       text-blue-600 flex items-center justify-center
                       shrink-0"
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
                        d="M12 14l9-5-9-5-9 5 9 5z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 14l6.16-3.422A12.083
                           12.083 0 0118 15.5c0 1.232.186
                           2.421.532 3.54L12 22l-6.532
                           -2.96A11.952 11.952 0 006
                           15.5c0-1.736-.366-3.386
                           -1.024-4.878L12 14z"
                    />
                </svg>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">
                    Data Siswa
                </p>

                <p class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $ringkasan['total_siswa'] ?? 0 }}

                    <span class="text-sm font-normal text-gray-500">
                        siswa
                    </span>
                </p>
            </div>
        </div>

        {{-- Guru --}}
        <div
            class="bg-white rounded-2xl border border-teal-100
                   border-l-4 border-l-teal-500 shadow-sm p-5
                   flex items-center gap-4"
        >
            <div
                class="w-12 h-12 rounded-full bg-teal-50
                       text-teal-600 flex items-center justify-center
                       shrink-0"
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
                        d="M5.121 17.804A9.003 9.003 0
                           0112 15c2.625 0 4.987 1.123
                           6.629 2.916M15 11a3 3 0
                           11-6 0 3 3 0 016 0zm6
                           1a9 9 0 11-18 0 9 9
                           0 0118 0z"
                    />
                </svg>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">
                    Data Guru
                </p>

                <p class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $ringkasan['total_guru'] ?? 0 }}

                    <span class="text-sm font-normal text-gray-500">
                        guru
                    </span>
                </p>
            </div>
        </div>

        {{-- Kelas --}}
        <div
            class="bg-white rounded-2xl border border-orange-100
                   border-l-4 border-l-orange-500 shadow-sm p-5
                   flex items-center gap-4"
        >
            <div
                class="w-12 h-12 rounded-full bg-orange-50
                       text-orange-600 flex items-center justify-center
                       shrink-0"
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
                        d="M3 10h18M5 6h14a2 2 0
                           012 2v10a2 2 0 01-2
                           2H5a2 2 0 01-2-2V8a2
                           2 0 012-2zm3 8h2m4
                           0h2"
                    />
                </svg>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">
                    Kelas Terdaftar
                </p>

                <p class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $ringkasan['total_kelas'] ?? 0 }}

                    <span class="text-sm font-normal text-gray-500">
                        kelas
                    </span>
                </p>
            </div>
        </div>
    </section>

    {{-- Aktivitas operasional --}}
    <section
        class="bg-white rounded-2xl border border-gray-100
               shadow-sm overflow-hidden"
    >
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">
                Ringkasan Operasional
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Statistik aktivitas sistem tanpa menampilkan isi laporan siswa.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">

            <div class="p-6">
                <p class="text-sm text-gray-500">
                    Check-in Minggu Ini
                </p>

                <p class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $ringkasan['checkin_minggu_ini'] ?? 0 }}
                </p>

                <p class="text-xs text-gray-400 mt-1">
                    Siswa unik yang sudah mengisi
                </p>
            </div>

            <div class="p-6">
                <p class="text-sm text-gray-500">
                    Laporan Menunggu
                </p>

                <p class="text-3xl font-bold text-orange-600 mt-2">
                    {{ $ringkasan['laporan_menunggu'] ?? 0 }}
                </p>

                <p class="text-xs text-gray-400 mt-1">
                    Menunggu peninjauan guru
                </p>
            </div>

            <div class="p-6">
                <p class="text-sm text-gray-500">
                    Tindak Lanjut Aktif
                </p>

                <p class="text-3xl font-bold text-teal-600 mt-2">
                    {{ $ringkasan['tindak_lanjut_aktif'] ?? 0 }}
                </p>

                <p class="text-xs text-gray-400 mt-1">
                    Pendampingan sedang berjalan
                </p>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Menu pengelolaan --}}
        <div class="xl:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">
                    Pengelolaan Data
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Akses fungsi administrasi utama.
                </p>
            </div>

            <div class="p-5 space-y-3">

                @if (Route::has('admin.users.index'))
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="flex items-center justify-between gap-4
                               rounded-xl border border-gray-100 p-4
                               hover:border-violet-200 hover:bg-violet-50
                               transition-colors"
                    >
                        <div>
                            <p class="font-semibold text-gray-800">
                                Manajemen Akun
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Tambah, ubah, dan kelola akun pengguna.
                            </p>
                        </div>

                        <span class="text-violet-600">→</span>
                    </a>
                @else
                    <div
                        class="flex items-center justify-between gap-4
                               rounded-xl border border-gray-100 p-4"
                    >
                        <div>
                            <p class="font-semibold text-gray-800">
                                Manajemen Akun
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Kelola akun admin, guru, dan siswa.
                            </p>
                        </div>

                        <span class="text-xs text-gray-400">
                            Segera
                        </span>
                    </div>
                @endif

                @if (Route::has('admin.kelas.index'))
                    <a
                        href="{{ route('admin.kelas.index') }}"
                        class="flex items-center justify-between gap-4
                               rounded-xl border border-gray-100 p-4
                               hover:border-orange-200 hover:bg-orange-50
                               transition-colors"
                    >
                        <div>
                            <p class="font-semibold text-gray-800">
                                Manajemen Kelas
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Kelola kelas dan anggota kelas.
                            </p>
                        </div>

                        <span class="text-orange-600">→</span>
                    </a>
                @else
                    <div
                        class="flex items-center justify-between gap-4
                               rounded-xl border border-gray-100 p-4"
                    >
                        <div>
                            <p class="font-semibold text-gray-800">
                                Manajemen Kelas
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Kelola data kelas sekolah.
                            </p>
                        </div>

                        <span class="text-xs text-gray-400">
                            Segera
                        </span>
                    </div>
                @endif

                @if (Route::has('admin.siswa.index'))
                    <a
                        href="{{ route('admin.siswa.index') }}"
                        class="flex items-center justify-between gap-4
                               rounded-xl border border-gray-100 p-4
                               hover:border-blue-200 hover:bg-blue-50
                               transition-colors"
                    >
                        <div>
                            <p class="font-semibold text-gray-800">
                                Data Siswa
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Periksa dan perbarui data siswa.
                            </p>
                        </div>

                        <span class="text-blue-600">→</span>
                    </a>
                @else
                    <div
                        class="flex items-center justify-between gap-4
                               rounded-xl border border-gray-100 p-4"
                    >
                        <div>
                            <p class="font-semibold text-gray-800">
                                Data Siswa
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Kelola identitas dan kelas siswa.
                            </p>
                        </div>

                        <span class="text-xs text-gray-400">
                            Segera
                        </span>
                    </div>
                @endif

                @if (Route::has('admin.guru.index'))
                    <a
                        href="{{ route('admin.guru.index') }}"
                        class="flex items-center justify-between gap-4
                               rounded-xl border border-gray-100 p-4
                               hover:border-teal-200 hover:bg-teal-50
                               transition-colors"
                    >
                        <div>
                            <p class="font-semibold text-gray-800">
                                Data Guru
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Kelola identitas dan jabatan guru.
                            </p>
                        </div>

                        <span class="text-teal-600">→</span>
                    </a>
                @else
                    <div
                        class="flex items-center justify-between gap-4
                               rounded-xl border border-gray-100 p-4"
                    >
                        <div>
                            <p class="font-semibold text-gray-800">
                                Data Guru
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                Kelola data guru pendamping.
                            </p>
                        </div>

                        <span class="text-xs text-gray-400">
                            Segera
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pengguna terbaru --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div
                class="px-6 py-5 border-b border-gray-100
                       flex flex-col sm:flex-row sm:items-center
                       sm:justify-between gap-3"
            >
                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        Pengguna Terbaru
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Lima akun yang terakhir dibuat.
                    </p>
                </div>

                @if (Route::has('admin.users.index'))
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="text-sm font-semibold text-violet-600
                               hover:text-violet-700"
                    >
                        Lihat semua pengguna →
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">
                                Pengguna
                            </th>

                            <th class="px-6 py-3 font-medium">
                                Peran
                            </th>

                            <th class="px-6 py-3 font-medium">
                                Dibuat
                            </th>

                            <th class="px-6 py-3 font-medium text-right">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($penggunaTerbaru as $pengguna)
                            @php
                                $namaPengguna =
                                    $pengguna->siswa?->nama
                                    ?? $pengguna->guru?->nama
                                    ?? $pengguna->name
                                    ?? $pengguna->email
                                    ?? 'Pengguna';

                                $role = strtolower(
                                    (string) ($pengguna->role ?? '')
                                );

                                $temaPeran = match ($role) {
                                    'admin' => [
                                        'label' => 'Admin',
                                        'class' =>
                                            'bg-violet-50 text-violet-700 border-violet-100',
                                    ],

                                    'guru' => [
                                        'label' => 'Guru',
                                        'class' =>
                                            'bg-teal-50 text-teal-700 border-teal-100',
                                    ],

                                    'siswa' => [
                                        'label' => 'Siswa',
                                        'class' =>
                                            'bg-blue-50 text-blue-700 border-blue-100',
                                    ],

                                    default => [
                                        'label' => 'Tidak diketahui',
                                        'class' =>
                                            'bg-gray-50 text-gray-600 border-gray-100',
                                    ],
                                };
                            @endphp

                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800">
                                        {{ $namaPengguna }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $pengguna->email ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex rounded-lg border
                                               px-2.5 py-1 text-xs font-semibold
                                               {{ $temaPeran['class'] }}"
                                    >
                                        {{ $temaPeran['label'] }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                    {{ $pengguna->created_at
                                        ? $pengguna->created_at
                                            ->translatedFormat('d M Y')
                                        : '-' }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="inline-flex rounded-lg border
                                               border-green-100 bg-green-50
                                               px-2.5 py-1 text-xs font-semibold
                                               text-green-700"
                                    >
                                        Aktif
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="4"
                                    class="px-6 py-10 text-center"
                                >
                                    <p class="text-sm font-medium text-gray-500">
                                        Belum ada data pengguna.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Distribusi peran --}}
    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div>
            <h2 class="text-lg font-bold text-gray-800">
                Distribusi Peran Pengguna
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Komposisi akun berdasarkan hak akses sistem.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

            {{-- Admin --}}
            <div>
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-gray-700">
                        Admin
                    </p>

                    <p class="text-sm font-bold text-violet-700">
                        {{ $distribusiPeran['admin'] ?? 0 }}
                    </p>
                </div>

                <div class="w-full h-2 rounded-full bg-gray-100 mt-3 overflow-hidden">
                    <div
                        class="h-full rounded-full bg-violet-500"
                        style="width: {{ min(100, $persentaseAdmin) }}%"
                    ></div>
                </div>

                <p class="text-xs text-gray-400 mt-2">
                    {{ number_format($persentaseAdmin, 1) }}%
                    dari seluruh akun
                </p>
            </div>

            {{-- Guru --}}
            <div>
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-gray-700">
                        Guru
                    </p>

                    <p class="text-sm font-bold text-teal-700">
                        {{ $distribusiPeran['guru'] ?? 0 }}
                    </p>
                </div>

                <div class="w-full h-2 rounded-full bg-gray-100 mt-3 overflow-hidden">
                    <div
                        class="h-full rounded-full bg-teal-500"
                        style="width: {{ min(100, $persentaseGuru) }}%"
                    ></div>
                </div>

                <p class="text-xs text-gray-400 mt-2">
                    {{ number_format($persentaseGuru, 1) }}%
                    dari seluruh akun
                </p>
            </div>

            {{-- Siswa --}}
            <div>
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-gray-700">
                        Siswa
                    </p>

                    <p class="text-sm font-bold text-blue-700">
                        {{ $distribusiPeran['siswa'] ?? 0 }}
                    </p>
                </div>

                <div class="w-full h-2 rounded-full bg-gray-100 mt-3 overflow-hidden">
                    <div
                        class="h-full rounded-full bg-blue-500"
                        style="width: {{ min(100, $persentaseSiswa) }}%"
                    ></div>
                </div>

                <p class="text-xs text-gray-400 mt-2">
                    {{ number_format($persentaseSiswa, 1) }}%
                    dari seluruh akun
                </p>
            </div>
        </div>
    </section>
</div>
@endsection