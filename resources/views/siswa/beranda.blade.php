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
    <div class="bg-gradient-to-r from-blue-600 to-teal-500 rounded-3xl p-8 md:p-10 text-white shadow-xl shadow-blue-200/50 mb-10 relative overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-48 h-48 bg-white opacity-20 rounded-full blur-3xl dark:hidden"></div>
        <div class="absolute bottom-0 left-10 -mb-10 w-32 h-32 bg-teal-300 opacity-20 rounded-full blur-2xl dark:hidden"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold mb-3 border border-white/30">Dashboard Siswa</span>
                <h1 class="text-3xl md:text-4xl font-black mb-3 tracking-tight">
                    Halo, {{ auth()->user()?->siswa?->nama ?? (auth()->user()?->email ?? 'Siswa') }}! 👋
                </h1>
                <p class="text-blue-50 text-sm md:text-base max-w-xl leading-relaxed">
                    Bagaimana kabarmu hari ini? Ingat, jangan ragu untuk bercerita jika ada hal yang mengganggu pikiranmu. Kami di sini untuk mendengarkan dan mendukungmu sepenuhnya.
                </p>
            </div>
            <div class="hidden md:block shrink-0">
                <div class="w-24 h-24 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center border border-white/20 shadow-inner">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Area Menu Utama -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Apa yang ingin kamu lakukan?</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Kartu Check-in Mingguan (Blue Theme) -->
        <div class="group bg-white p-7 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl hover:shadow-blue-100/50 hover:border-blue-200 transition-all duration-300 hover:-translate-y-1">
            <div>
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-700 transition-colors">Check-in Harian</h3>
                <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                    Bagikan perasaan dan suasana belajarmu hari ini. Hanya butuh 1 menit untuk membantu kami memahamimu lebih baik.
                </p>
            </div>
            <a href="{{ route('siswa.checkin.create') }}" class="block w-full text-center bg-blue-50 group-hover:bg-blue-600 text-blue-700 group-hover:text-white font-bold py-3.5 rounded-xl transition-all duration-300">
                Mulai Check-in
            </a>
        </div>

        <!-- Kartu Safe Report (Amber/Orange Theme) -->
        <div class="group bg-white p-7 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl hover:shadow-amber-100/50 hover:border-amber-200 transition-all duration-300 hover:-translate-y-1">
            <div>
                <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex items-center gap-3 mb-2">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-amber-600 transition-colors">Safe Report</h3>
                    <span class="px-2 py-0.5 bg-red-100 text-red-600 text-[10px] font-black rounded-full uppercase tracking-wider animate-pulse">Penting</span>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    Melihat atau mengalami hal tidak nyaman? Laporkan secara aman di sini. Kamu dapat mengirim laporan secara anonim.
                </p>
            </div>
            <a href="{{ route('siswa.report.create') }}" class="block w-full text-center bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-md shadow-amber-200">
                Buat Laporan Darurat
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
