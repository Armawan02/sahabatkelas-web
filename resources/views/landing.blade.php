<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="SahabatKelas adalah platform berbasis AI untuk membantu sekolah mendeteksi dini risiko perundungan, memetakan kondisi siswa, dan mendukung pendampingan yang berkelanjutan."
    >

    <title>SahabatKelas — Sekolah Aman, Siswa Nyaman</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        html {
            scroll-behavior: smooth;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .hero-grid {
            background-image:
                linear-gradient(
                    rgba(20, 184, 166, 0.08) 1px,
                    transparent 1px
                ),
                linear-gradient(
                    90deg,
                    rgba(20, 184, 166, 0.08) 1px,
                    transparent 1px
                );

            background-size: 32px 32px;
        }
    </style>
</head>

<body class="bg-white text-gray-800 antialiased">
@php
    $user = auth()->user();

    $dashboardRoute = match ($user?->role) {
        'admin' => Route::has('admin.dashboard')
            ? route('admin.dashboard')
            : '#',

        'guru' => Route::has('guru.dashboard')
            ? route('guru.dashboard')
            : '#',

        'siswa' => Route::has('siswa.beranda')
            ? route('siswa.beranda')
            : '#',

        default => Route::has('login')
            ? route('login')
            : url('/login'),
    };

    $dashboardLabel = $user
        ? 'Buka Dashboard'
        : 'Masuk ke Platform';
@endphp

{{-- Navbar --}}
<header
    class="sticky top-0 z-50 border-b border-gray-100
           bg-white/90 backdrop-blur-xl"
>
    <nav
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
               h-16 flex items-center justify-between"
    >
        {{-- Logo --}}
        <a
            href="{{ route('landing') }}"
            class="flex items-center gap-3"
        >
            <div
                class="w-10 h-10 rounded-2xl bg-teal-600
                       flex items-center justify-center
                       shadow-sm shadow-teal-200"
            >
                <svg
                    class="w-6 h-6 text-white"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01
                           M9 16H5a2 2 0 01-2-2V6a2 2
                           0 012-2h14a2 2 0 012 2v8a2
                           2 0 01-2 2h-5l-3 3v-3z"
                    />
                </svg>
            </div>

            <div>
                <p class="text-lg font-bold text-gray-900 leading-none">
                    SahabatKelas
                </p>

                <p class="text-[10px] font-semibold text-teal-600 mt-1">
                    SEKOLAH AMAN, SISWA NYAMAN
                </p>
            </div>
        </a>

        {{-- Menu desktop --}}
        <div
            class="hidden lg:flex items-center gap-8
                   text-sm font-medium text-gray-600"
        >
            <a
                href="#tentang"
                class="hover:text-teal-600 transition-colors"
            >
                Tentang
            </a>

            <a
                href="#fitur"
                class="hover:text-teal-600 transition-colors"
            >
                Fitur
            </a>

            <a
                href="#alur"
                class="hover:text-teal-600 transition-colors"
            >
                Cara Kerja
            </a>

            <a
                href="#pengguna"
                class="hover:text-teal-600 transition-colors"
            >
                Pengguna
            </a>
        </div>

        {{-- Tombol kanan --}}
        <div class="flex items-center gap-3">
            @auth
                <span
                    class="hidden md:inline text-sm text-gray-500"
                >
                    Halo,
                    {{ $user?->siswa?->nama
                        ?? $user?->guru?->nama
                        ?? $user?->name
                        ?? $user?->email }}
                </span>
            @endauth

            <a
                href="{{ $dashboardRoute }}"
                class="inline-flex items-center justify-center
                       rounded-xl bg-teal-600 px-4 py-2.5
                       text-sm font-semibold text-white
                       hover:bg-teal-700 transition-colors
                       shadow-sm shadow-teal-200"
            >
                {{ $dashboardLabel }}
            </a>
        </div>
    </nav>
</header>

<main>
    {{-- Hero --}}
    <section
        class="relative overflow-hidden bg-gradient-to-b
               from-teal-50/80 via-white to-white hero-grid"
    >
        <div
            class="absolute -top-24 -right-24 w-96 h-96
                   rounded-full bg-teal-200/30 blur-3xl"
        ></div>

        <div
            class="absolute top-60 -left-32 w-80 h-80
                   rounded-full bg-blue-200/20 blur-3xl"
        ></div>

        <div
            class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
                   pt-16 pb-20 lg:pt-24 lg:pb-28"
        >
            <div
                class="grid grid-cols-1 lg:grid-cols-2
                       items-center gap-12"
            >
                {{-- Teks hero --}}
                <div>
                    <div
                        class="inline-flex items-center gap-2
                               rounded-full border border-teal-200
                               bg-white px-4 py-2 text-xs
                               font-semibold text-teal-700 shadow-sm"
                    >
                        <span class="relative flex w-2.5 h-2.5">
                            <span
                                class="absolute inline-flex w-full h-full
                                       rounded-full bg-teal-400 opacity-75
                                       animate-ping"
                            ></span>

                            <span
                                class="relative inline-flex w-2.5 h-2.5
                                       rounded-full bg-teal-500"
                            ></span>
                        </span>

                        Deteksi dini dan pendampingan berbasis data
                    </div>

                    <h1
                        class="mt-6 text-4xl sm:text-5xl lg:text-6xl
                               font-black tracking-tight text-gray-900
                               leading-tight"
                    >
                        Membantu sekolah
                        <span class="text-teal-600">
                            memahami kondisi siswa
                        </span>
                        lebih awal.
                    </h1>

                    <p
                        class="mt-6 max-w-2xl text-base sm:text-lg
                               leading-relaxed text-gray-600"
                    >
                        SahabatKelas mengintegrasikan check-in siswa,
                        laporan aman, observasi guru, analisis IndoBERT,
                        pemetaan risiko, rekomendasi pendampingan, serta
                        monitoring intervensi dalam satu platform.
                    </p>

                    <div
                        class="mt-8 flex flex-col sm:flex-row
                               items-stretch sm:items-center gap-3"
                    >
                        <a
                            href="{{ $dashboardRoute }}"
                            class="inline-flex items-center justify-center
                                   gap-2 rounded-xl bg-teal-600
                                   px-6 py-3.5 text-sm font-bold text-white
                                   hover:bg-teal-700 transition-colors
                                   shadow-lg shadow-teal-200"
                        >
                            {{ $dashboardLabel }}

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </a>

                        <a
                            href="#fitur"
                            class="inline-flex items-center justify-center
                                   rounded-xl border border-gray-200 bg-white
                                   px-6 py-3.5 text-sm font-bold
                                   text-gray-700 hover:bg-gray-50
                                   transition-colors"
                        >
                            Pelajari Fitur
                        </a>
                    </div>

                    <div
                        class="mt-9 flex flex-wrap items-center
                               gap-x-6 gap-y-3 text-sm text-gray-500"
                    >
                        <div class="flex items-center gap-2">
                            <svg
                                class="w-5 h-5 text-teal-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                            Laporan aman
                        </div>

                        <div class="flex items-center gap-2">
                            <svg
                                class="w-5 h-5 text-teal-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                            Analisis AI
                        </div>

                        <div class="flex items-center gap-2">
                            <svg
                                class="w-5 h-5 text-teal-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                            Monitoring berkelanjutan
                        </div>
                    </div>
                </div>

                {{-- Visual dashboard --}}
                <div class="relative">
                    <div
                        class="absolute -inset-5 rounded-[2rem]
                               bg-gradient-to-r from-teal-200/40
                               to-blue-200/30 blur-2xl"
                    ></div>

                    <div
                        class="relative rounded-3xl border border-gray-200
                               bg-white p-4 sm:p-6 shadow-2xl"
                    >
                        {{-- Header visual --}}
                        <div
                            class="flex items-center justify-between
                                   border-b border-gray-100 pb-4"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-teal-100
                                           flex items-center justify-center"
                                >
                                    <svg
                                        class="w-5 h-5 text-teal-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7"
                                        />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-sm font-bold text-gray-800">
                                        Insight Kelas
                                    </p>

                                    <p class="text-xs text-gray-400">
                                        Kondisi siswa hari ini
                                    </p>
                                </div>
                            </div>

                            <span
                                class="rounded-full bg-green-50
                                       px-3 py-1 text-xs font-semibold
                                       text-green-700"
                            >
                                Sistem Aktif
                            </span>
                        </div>

                        {{-- Statistik --}}
                        <div
                            class="grid grid-cols-2 sm:grid-cols-3
                                   gap-3 mt-5"
                        >
                            <div
                                class="rounded-2xl border border-red-100
                                       bg-red-50 p-4"
                            >
                                <p class="text-xs text-red-600">
                                    Risiko Tinggi
                                </p>

                                <p
                                    class="mt-2 text-2xl font-black
                                           text-red-700"
                                >
                                    3
                                </p>
                            </div>

                            <div
                                class="rounded-2xl border border-yellow-100
                                       bg-yellow-50 p-4"
                            >
                                <p class="text-xs text-yellow-700">
                                    Risiko Sedang
                                </p>

                                <p
                                    class="mt-2 text-2xl font-black
                                           text-yellow-700"
                                >
                                    7
                                </p>
                            </div>

                            <div
                                class="col-span-2 sm:col-span-1
                                       rounded-2xl border border-green-100
                                       bg-green-50 p-4"
                            >
                                <p class="text-xs text-green-700">
                                    Risiko Rendah
                                </p>

                                <p
                                    class="mt-2 text-2xl font-black
                                           text-green-700"
                                >
                                    20
                                </p>
                            </div>
                        </div>

                        {{-- Heatmap --}}
                        <div
                            class="mt-5 rounded-2xl border
                                   border-gray-100 p-4"
                        >
                            <div
                                class="flex items-center justify-between"
                            >
                                <div>
                                    <p class="text-sm font-bold text-gray-800">
                                        Heatmap Risiko Kelas
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Ringkasan berdasarkan analisis terbaru
                                    </p>
                                </div>

                                <span
                                    class="text-xs font-semibold
                                           text-teal-600"
                                >
                                    Kelas VIII A
                                </span>
                            </div>

                            <div
                                class="grid grid-cols-6 sm:grid-cols-8
                                       gap-2 mt-4"
                            >
                                @foreach ([
                                    'green', 'green', 'green', 'yellow',
                                    'green', 'red', 'green', 'yellow',
                                    'green', 'green', 'yellow', 'green',
                                    'red', 'green', 'green', 'yellow',
                                    'green', 'green', 'green', 'red',
                                    'green', 'yellow', 'green', 'green',
                                ] as $warna)
                                    <div
                                        class="aspect-square rounded-lg
                                            {{ $warna === 'red'
                                                ? 'bg-red-400'
                                                : (
                                                    $warna === 'yellow'
                                                    ? 'bg-yellow-300'
                                                    : 'bg-green-300'
                                                ) }}"
                                    ></div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Rekomendasi --}}
                        <div
                            class="mt-5 rounded-2xl bg-gray-50
                                   border border-gray-100 p-4"
                        >
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-9 h-9 rounded-xl bg-teal-100
                                           flex items-center justify-center
                                           shrink-0"
                                >
                                    <svg
                                        class="w-5 h-5 text-teal-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618
                                               -4.016A11.955 11.955
                                               0 0112 2.944a11.955
                                               11.955 0 01-8.618
                                               3.04A12.02 12.02
                                               0 003 9c0 5.591
                                               3.824 10.29 9
                                               11.622 5.176-1.332
                                               9-6.03 9-11.622
                                               0-1.042-.133-2.052
                                               -.382-3.016z"
                                        />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-400">
                                        Rekomendasi Pendampingan
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-semibold
                                               text-gray-800"
                                    >
                                        Konseling individual dan pemantauan
                                        kondisi siswa secara berkala.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating card --}}
                    <div
                        class="absolute -bottom-6 -left-3 sm:-left-8
                               rounded-2xl border border-gray-100
                               bg-white px-4 py-3 shadow-xl"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-50
                                       flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2
                                           5H7a2 2 0 01-2-2V5
                                           a2 2 0 012-2h5.586a1
                                           1 0 01.707.293l3.414
                                           3.414a1 1 0 01.293
                                           .707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                            </div>

                            <div>
                                <p class="text-xs text-gray-400">
                                    Safe Report
                                </p>

                                <p class="text-sm font-bold text-gray-800">
                                    Analisis selesai
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tentang --}}
    <section
        id="tentang"
        class="py-20 bg-white"
    >
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
        >
            <div
                class="grid grid-cols-1 lg:grid-cols-2
                       items-center gap-12"
            >
                <div>
                    <p
                        class="text-sm font-bold uppercase tracking-wider
                               text-teal-600"
                    >
                        Mengapa SahabatKelas?
                    </p>

                    <h2
                        class="mt-3 text-3xl sm:text-4xl font-black
                               tracking-tight text-gray-900"
                    >
                        Risiko perundungan perlu diketahui sebelum
                        kondisinya semakin berat.
                    </h2>

                    <p
                        class="mt-5 text-base leading-relaxed
                               text-gray-600"
                    >
                        Informasi kondisi siswa sering tersebar pada
                        laporan, observasi, dan komunikasi yang berbeda.
                        SahabatKelas membantu sekolah menyatukan informasi
                        tersebut menjadi insight yang lebih mudah dipahami
                        dan ditindaklanjuti.
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                >
                    <article
                        class="rounded-2xl border border-gray-100
                               bg-gray-50 p-6"
                    >
                        <div
                            class="w-11 h-11 rounded-xl bg-blue-100
                                   text-blue-600 flex items-center
                                   justify-center"
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
                                    d="M8 10h.01M12 10h.01M16
                                       10h.01M9 16H5a2 2 0
                                       01-2-2V6a2 2 0 012-2h14
                                       a2 2 0 012 2v8a2 2 0
                                       01-2 2h-5l-3 3v-3z"
                                />
                            </svg>
                        </div>

                        <h3
                            class="mt-4 font-bold text-gray-800"
                        >
                            Ruang bercerita yang aman
                        </h3>

                        <p
                            class="mt-2 text-sm leading-relaxed
                                   text-gray-500"
                        >
                            Siswa dapat melakukan check-in dan mengirim
                            laporan dengan pilihan anonim.
                        </p>
                    </article>

                    <article
                        class="rounded-2xl border border-gray-100
                               bg-gray-50 p-6"
                    >
                        <div
                            class="w-11 h-11 rounded-xl bg-teal-100
                                   text-teal-600 flex items-center
                                   justify-center"
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
                                    d="M9.75 17L9 20l-1 1h8l-1-1
                                       -.75-3M3 13h18M5 17h14a2
                                       2 0 002-2V5a2 2 0 00-2
                                       -2H5a2 2 0 00-2 2v10a2
                                       2 0 002 2z"
                                />
                            </svg>
                        </div>

                        <h3
                            class="mt-4 font-bold text-gray-800"
                        >
                            Insight berbasis AI
                        </h3>

                        <p
                            class="mt-2 text-sm leading-relaxed
                                   text-gray-500"
                        >
                            IndoBERT membantu mengklasifikasikan indikasi
                            laporan dan mendukung pemetaan risiko.
                        </p>
                    </article>

                    <article
                        class="rounded-2xl border border-gray-100
                               bg-gray-50 p-6"
                    >
                        <div
                            class="w-11 h-11 rounded-xl bg-orange-100
                                   text-orange-600 flex items-center
                                   justify-center"
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
                                    d="M9 17v-2a4 4 0 014-4h4
                                       m0 0l-3-3m3 3l-3 3M5
                                       21a2 2 0 01-2-2V5a2
                                       2 0 012-2h6a2 2 0
                                       012 2v4"
                                />
                            </svg>
                        </div>

                        <h3
                            class="mt-4 font-bold text-gray-800"
                        >
                            Rekomendasi pendampingan
                        </h3>

                        <p
                            class="mt-2 text-sm leading-relaxed
                                   text-gray-500"
                        >
                            Guru memperoleh rekomendasi awal berdasarkan
                            kategori dan tingkat risiko siswa.
                        </p>
                    </article>

                    <article
                        class="rounded-2xl border border-gray-100
                               bg-gray-50 p-6"
                    >
                        <div
                            class="w-11 h-11 rounded-xl bg-green-100
                                   text-green-600 flex items-center
                                   justify-center"
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
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>
                        </div>

                        <h3
                            class="mt-4 font-bold text-gray-800"
                        >
                            Monitoring berkelanjutan
                        </h3>

                        <p
                            class="mt-2 text-sm leading-relaxed
                                   text-gray-500"
                        >
                            Perkembangan siswa dapat dicatat untuk melihat
                            kondisi membaik, tetap, atau memburuk.
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- Fitur --}}
    <section
        id="fitur"
        class="py-20 bg-gray-50"
    >
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
        >
            <div class="max-w-3xl mx-auto text-center">
                <p
                    class="text-sm font-bold uppercase tracking-wider
                           text-teal-600"
                >
                    Fitur Utama
                </p>

                <h2
                    class="mt-3 text-3xl sm:text-4xl font-black
                           tracking-tight text-gray-900"
                >
                    Satu platform untuk deteksi, pendampingan,
                    dan evaluasi.
                </h2>

                <p
                    class="mt-4 text-base leading-relaxed text-gray-600"
                >
                    Seluruh alur dirancang agar siswa dapat bercerita
                    dengan aman dan guru dapat bertindak secara lebih
                    terarah.
                </p>
            </div>

            <div
                class="mt-12 grid grid-cols-1 md:grid-cols-2
                       lg:grid-cols-3 gap-6"
            >
                @php
                    $fitur = [
                        [
                            'nomor' => '01',
                            'judul' => 'Check-in Mingguan',
                            'deskripsi' =>
                                'Membantu siswa menyampaikan kondisi keamanan, emosi, interaksi sosial, dan pengalaman belajar secara berkala.',
                        ],
                        [
                            'nomor' => '02',
                            'judul' => 'Safe Report',
                            'deskripsi' =>
                                'Pelaporan kejadian tidak nyaman atau perundungan dengan dukungan pilihan anonim dan informasi kejadian terstruktur.',
                        ],
                        [
                            'nomor' => '03',
                            'judul' => 'Analisis IndoBERT',
                            'deskripsi' =>
                                'Teks laporan dianalisis ke dalam kategori fisik, verbal, sosial, siber, atau bukan perundungan.',
                        ],
                        [
                            'nomor' => '04',
                            'judul' => 'Heatmap Risiko',
                            'deskripsi' =>
                                'Guru dapat melihat gambaran kondisi kelas dan memprioritaskan siswa yang membutuhkan perhatian.',
                        ],
                        [
                            'nomor' => '05',
                            'judul' => 'Rekomendasi Pendampingan',
                            'deskripsi' =>
                                'Sistem menghasilkan rekomendasi awal, sementara keputusan akhir tetap berada pada guru.',
                        ],
                        [
                            'nomor' => '06',
                            'judul' => 'Monitoring Intervensi',
                            'deskripsi' =>
                                'Tindak lanjut dan perubahan kondisi siswa dicatat dalam timeline yang terstruktur.',
                        ],
                    ];
                @endphp

                @foreach ($fitur as $item)
                    <article
                        class="group rounded-3xl border border-gray-100
                               bg-white p-6 shadow-sm
                               hover:-translate-y-1 hover:shadow-lg
                               transition-all"
                    >
                        <div
                            class="flex items-center justify-between"
                        >
                            <span
                                class="text-3xl font-black text-teal-100
                                       group-hover:text-teal-200
                                       transition-colors"
                            >
                                {{ $item['nomor'] }}
                            </span>

                            <div
                                class="w-10 h-10 rounded-xl bg-teal-50
                                       flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-teal-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>
                        </div>

                        <h3
                            class="mt-5 text-lg font-bold text-gray-900"
                        >
                            {{ $item['judul'] }}
                        </h3>

                        <p
                            class="mt-3 text-sm leading-relaxed
                                   text-gray-500"
                        >
                            {{ $item['deskripsi'] }}
                        </p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Alur --}}
    <section
        id="alur"
        class="py-20 bg-white"
    >
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
        >
            <div class="max-w-3xl">
                <p
                    class="text-sm font-bold uppercase tracking-wider
                           text-teal-600"
                >
                    Cara Kerja
                </p>

                <h2
                    class="mt-3 text-3xl sm:text-4xl font-black
                           tracking-tight text-gray-900"
                >
                    Dari suara siswa menjadi tindakan nyata.
                </h2>

                <p
                    class="mt-4 text-base leading-relaxed text-gray-600"
                >
                    SahabatKelas membangun alur pendampingan yang
                    terhubung dari pengumpulan informasi hingga
                    monitoring perubahan kondisi siswa.
                </p>
            </div>

            <div
                class="mt-12 grid grid-cols-1 md:grid-cols-5 gap-4"
            >
                @php
                    $alur = [
                        [
                            'angka' => '1',
                            'judul' => 'Siswa Mengisi',
                            'deskripsi' =>
                                'Check-in dan Safe Report.',
                        ],
                        [
                            'angka' => '2',
                            'judul' => 'AI Menganalisis',
                            'deskripsi' =>
                                'Indikasi laporan diklasifikasikan.',
                        ],
                        [
                            'angka' => '3',
                            'judul' => 'Risiko Dipetakan',
                            'deskripsi' =>
                                'Data digabung menjadi tingkat risiko.',
                        ],
                        [
                            'angka' => '4',
                            'judul' => 'Guru Bertindak',
                            'deskripsi' =>
                                'Rekomendasi dipilih dan dilaksanakan.',
                        ],
                        [
                            'angka' => '5',
                            'judul' => 'Kondisi Dimonitor',
                            'deskripsi' =>
                                'Perubahan siswa dicatat berkala.',
                        ],
                    ];
                @endphp

                @foreach ($alur as $item)
                    <article
                        class="relative rounded-2xl border
                               border-gray-100 bg-gray-50 p-5"
                    >
                        <div
                            class="w-10 h-10 rounded-full bg-teal-600
                                   text-white flex items-center
                                   justify-center font-bold"
                        >
                            {{ $item['angka'] }}
                        </div>

                        <h3
                            class="mt-4 font-bold text-gray-900"
                        >
                            {{ $item['judul'] }}
                        </h3>

                        <p
                            class="mt-2 text-sm leading-relaxed
                                   text-gray-500"
                        >
                            {{ $item['deskripsi'] }}
                        </p>

                        @if (!$loop->last)
                            <div
                                class="hidden md:flex absolute
                                       -right-4 top-1/2 -translate-y-1/2
                                       z-10 w-8 h-8 rounded-full bg-white
                                       border border-gray-200
                                       items-center justify-center"
                            >
                                <svg
                                    class="w-4 h-4 text-teal-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pengguna --}}
    <section
        id="pengguna"
        class="py-20 bg-slate-900"
    >
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
        >
            <div class="max-w-3xl mx-auto text-center">
                <p
                    class="text-sm font-bold uppercase tracking-wider
                           text-teal-400"
                >
                    Dirancang untuk Ekosistem Sekolah
                </p>

                <h2
                    class="mt-3 text-3xl sm:text-4xl font-black
                           tracking-tight text-white"
                >
                    Setiap pengguna mendapatkan fungsi sesuai
                    kebutuhannya.
                </h2>
            </div>

            <div
                class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6"
            >
                {{-- Siswa --}}
                <article
                    class="rounded-3xl border border-white/10
                           bg-white/5 p-7 backdrop-blur-sm"
                >
                    <div
                        class="w-12 h-12 rounded-2xl bg-blue-500/20
                               text-blue-300 flex items-center
                               justify-center"
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
                                d="M12 14l9-5-9-5-9 5 9
                                   5zm0 0l6.16-3.422A12.083
                                   12.083 0 0118 15.5c0
                                   1.232.186 2.421.532
                                   3.54L12 22l-6.532-2.96A11.952
                                   11.952 0 006 15.5c0-1.736
                                   -.366-3.386-1.024-4.878L12 14z"
                            />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-xl font-bold text-white">
                        Siswa
                    </h3>

                    <p
                        class="mt-3 text-sm leading-relaxed
                               text-slate-300"
                    >
                        Mengisi check-in mingguan dan mengirim laporan
                        secara aman ketika mengalami atau menyaksikan
                        kejadian tidak nyaman.
                    </p>
                </article>

                {{-- Guru --}}
                <article
                    class="rounded-3xl border border-teal-400/30
                           bg-teal-500/10 p-7 backdrop-blur-sm"
                >
                    <div
                        class="w-12 h-12 rounded-2xl bg-teal-500/20
                               text-teal-300 flex items-center
                               justify-center"
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
                                d="M5.121 17.804A9.003
                                   9.003 0 0112 15c2.625
                                   0 4.987 1.123 6.629
                                   2.916M15 11a3 3 0
                                   11-6 0 3 3 0 016
                                   0zm6 1a9 9 0
                                   11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-xl font-bold text-white">
                        Guru BK dan Wali Kelas
                    </h3>

                    <p
                        class="mt-3 text-sm leading-relaxed
                               text-slate-300"
                    >
                        Memantau heatmap, melihat prioritas risiko,
                        memperoleh rekomendasi, mencatat tindak lanjut,
                        dan melakukan monitoring.
                    </p>
                </article>

                {{-- Admin --}}
                <article
                    class="rounded-3xl border border-white/10
                           bg-white/5 p-7 backdrop-blur-sm"
                >
                    <div
                        class="w-12 h-12 rounded-2xl bg-violet-500/20
                               text-violet-300 flex items-center
                               justify-center"
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
                                d="M9.75 3a.75.75 0
                                   01.75.75V5h3V3.75a.75.75
                                   0 011.5 0V5h.75A2.25
                                   2.25 0 0118 7.25v.75h1.25
                                   a.75.75 0 010 1.5H18v3h1.25
                                   a.75.75 0 010 1.5H18v.75A2.25
                                   2.25 0 0115.75 17H15v1.25
                                   a.75.75 0 01-1.5 0V17h-3
                                   v1.25a.75.75 0 01-1.5 0V17
                                   h-.75A2.25 2.25 0 016 14.75V14
                                   H4.75a.75.75 0 010-1.5H6v-3
                                   H4.75a.75.75 0 010-1.5H6v-.75
                                   A2.25 2.25 0 018.25 5H9V3.75
                                   A.75.75 0 019.75 3z"
                            />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-xl font-bold text-white">
                        Administrator
                    </h3>

                    <p
                        class="mt-3 text-sm leading-relaxed
                               text-slate-300"
                    >
                        Mengelola akun, data guru, siswa, kelas, serta
                        memastikan data operasional platform tetap
                        terorganisasi.
                    </p>
                </article>
            </div>
        </div>
    </section>

    {{-- Privasi --}}
    <section class="py-20 bg-white">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
        >
            <div
                class="rounded-3xl border border-teal-100
                       bg-gradient-to-r from-teal-50 to-blue-50
                       p-7 sm:p-10"
            >
                <div
                    class="grid grid-cols-1 lg:grid-cols-3
                           items-center gap-8"
                >
                    <div
                        class="w-16 h-16 rounded-2xl bg-teal-600
                               flex items-center justify-center"
                    >
                        <svg
                            class="w-8 h-8 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2
                                   0 002-2v-6a2 2 0 00-2
                                   -2H6a2 2 0 00-2 2v6a2
                                   2 0 002 2zm10-10V7a4
                                   4 0 00-8 0v4h8z"
                            />
                        </svg>
                    </div>

                    <div class="lg:col-span-2">
                        <h2
                            class="text-2xl sm:text-3xl font-black
                                   text-gray-900"
                        >
                            Teknologi mendukung keputusan, bukan
                            menggantikan peran guru.
                        </h2>

                        <p
                            class="mt-4 text-sm sm:text-base
                                   leading-relaxed text-gray-600"
                        >
                            Hasil analisis SahabatKelas berfungsi sebagai
                            informasi pendukung. Penilaian, verifikasi,
                            dan keputusan tindak lanjut tetap dilakukan
                            oleh guru atau pihak sekolah yang berwenang.
                            Identitas laporan anonim tidak ditampilkan
                            sebagai identitas pelapor.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="pb-20 bg-white">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
        >
            <div
                class="relative overflow-hidden rounded-3xl
                       bg-teal-600 px-6 py-12 sm:px-12
                       text-center shadow-xl shadow-teal-200"
            >
                <div
                    class="absolute -top-20 -left-20 w-60 h-60
                           rounded-full bg-white/10"
                ></div>

                <div
                    class="absolute -bottom-24 -right-20 w-72 h-72
                           rounded-full bg-white/10"
                ></div>

                <div class="relative z-10 max-w-3xl mx-auto">
                    <h2
                        class="text-3xl sm:text-4xl font-black
                               text-white"
                    >
                        Bersama membangun kelas yang lebih aman,
                        peduli, dan suportif.
                    </h2>

                    <p
                        class="mt-4 text-base leading-relaxed
                               text-teal-50"
                    >
                        Gunakan SahabatKelas untuk mendukung deteksi dini,
                        pendampingan personal, dan pemantauan kondisi
                        siswa secara berkelanjutan.
                    </p>

                    <a
                        href="{{ $dashboardRoute }}"
                        class="mt-7 inline-flex items-center justify-center
                               gap-2 rounded-xl bg-white px-6 py-3.5
                               text-sm font-bold text-teal-700
                               hover:bg-teal-50 transition-colors"
                    >
                        {{ $dashboardLabel }}

                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

{{-- Footer --}}
<footer class="border-t border-gray-100 bg-gray-50">
    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
               py-8 flex flex-col md:flex-row
               md:items-center md:justify-between gap-5"
    >
        <div class="flex items-center gap-3">
            <div
                class="w-9 h-9 rounded-xl bg-teal-600
                       text-white flex items-center justify-center"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 10h.01M12 10h.01M16
                           10h.01M9 16H5a2 2 0
                           01-2-2V6a2 2 0 012-2h14
                           a2 2 0 012 2v8a2 2 0
                           01-2 2h-5l-3 3v-3z"
                    />
                </svg>
            </div>

            <div>
                <p class="font-bold text-gray-900">
                    SahabatKelas
                </p>

                <p class="text-xs text-gray-500">
                    Platform Intelligent Student Insight
                </p>
            </div>
        </div>

        <p class="text-sm text-gray-500">
            © {{ now()->year }} SahabatKelas.
            Dikembangkan untuk inovasi pendidikan.
        </p>
    </div>
</footer>
</body>
</html>