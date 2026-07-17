@extends('layouts.app')

@section('title', 'Beranda - SahabatKelas')

@section('content')
    <!-- Notifikasi Sukses (Muncul jika ada session 'success' dari Controller) -->
    @if (session('success'))
        <div class="bg-teal-50 border border-teal-200 text-teal-800 px-4 py-3 rounded-xl mb-6 flex items-start shadow-sm">
            <svg class="w-5 h-5 mr-3 mt-0.5 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Kartu Sambutan (Hero Section) -->
    <div
        class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl p-6 md:p-8 text-white shadow-md mb-8 relative overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>

        <div class="relative z-10">
            <h1 class="text-2xl md:text-3xl font-bold mb-2">
                Halo,
                {{ auth()->user()?->siswa?->nama ?? (auth()->user()?->email ?? 'Siswa') }}! 👋
            </h1>
            <p class="text-teal-50 text-sm md:text-base max-w-xl leading-relaxed">
                Bagaimana kabarmu hari ini? Kami harap kamu menjalani minggu yang menyenangkan.
                Ingat, jangan ragu untuk bercerita jika ada hal yang mengganggu pikiranmu. Kami di sini untuk mendengarkan
                dan mendukungmu.
            </p>
        </div>
    </div>

    <!-- Area Menu Utama -->
    <h2 class="text-lg font-bold text-gray-800 mb-4">Apa yang ingin kamu lakukan?</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

        <!-- Kartu Check-in Mingguan -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between hover:border-blue-300 transition-colors">
            <div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Check-in Mingguan</h3>
                <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                    Ceritakan sedikit tentang perasaan dan suasana belajarmu minggu ini. Hanya butuh 1-2 menit saja!
                </p>
            </div>
            <a href="{{ route('siswa.checkin.create') }}"
                class="block w-full text-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold py-2.5 rounded-xl transition-colors">
                Mulai Check-in
            </a>
        </div>

        <!-- Kartu Safe Report -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between hover:border-teal-300 transition-colors">
            <div>
                <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Safe Report</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Mengalami atau melihat kejadian tidak nyaman atau perundungan?
                    Laporkan secara aman melalui halaman ini.
                </p>

                <p class="text-xs text-gray-500 mt-2 mb-6">
                    Kamu dapat memilih untuk mengirim laporan secara anonim.
                </p>
            </div>
            <a href="{{ route('siswa.report.create') }}"
                class="block w-full text-center bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 rounded-xl transition-colors shadow-sm">
                Buat Laporan Baru
            </a>
        </div>

    </div>

    <!-- Pesan Edukasi / Dukungan Bawah -->
    <div class="mt-8 bg-orange-50 border border-orange-100 rounded-2xl p-5 flex items-start">
        <svg class="w-6 h-6 text-orange-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-sm text-gray-700 leading-relaxed">
            <strong>Pusat Bantuan Cepat:</strong> Jika kamu merasa dalam kondisi darurat atau membutuhkan teman bicara
            secepatnya, kamu selalu bisa menemui Guru BK atau Wali Kelasmu secara langsung. Kamu tidak sendirian.
        </p>
    </div>
@endsection
