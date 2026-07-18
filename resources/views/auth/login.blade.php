<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SahabatKelas adalah platform berbasis AI untuk membantu sekolah mendeteksi dini risiko perundungan, memetakan kondisi siswa, dan mendukung pendampingan yang berkelanjutan.">
    <title>SahabatKelas — Sekolah Aman, Siswa Nyaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html { scroll-behavior: smooth; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans">
@php
    $user = auth()->user();
    $dashboardRoute = match ($user?->role) {
        'admin' => Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin'),
        'guru' => Route::has('guru.dashboard') ? route('guru.dashboard') : url('/guru'),
        'siswa' => Route::has('siswa.beranda') ? route('siswa.beranda') : url('/siswa'),
        default => route('login'),
    };
    $dashboardLabel = match ($user?->role) {
        'admin' => 'Dashboard Admin',
        'guru' => 'Dashboard Guru',
        'siswa' => 'Dashboard Siswa',
        default => 'Masuk / Daftar',
    };
@endphp

<!-- Navbar -->
<header class="fixed top-0 w-full z-50 transition-all duration-300 bg-white/40 backdrop-blur-md border-b border-white/20">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="/img/logo.png" alt="Logo" class="h-8 w-auto">
            <div>
                <span class="text-xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-teal-500">SahabatKelas</span>
            </div>
        </div>
        
        <div class="hidden lg:flex items-center gap-8 font-semibold text-sm text-gray-600">
            <a href="#beranda" class="hover:text-teal-600 transition-colors">Beranda</a>
            <a href="#tentang" class="hover:text-teal-600 transition-colors">Tentang</a>
            <a href="#statistik" class="hover:text-teal-600 transition-colors">Statistik</a>
            <a href="#artikel" class="hover:text-teal-600 transition-colors">Artikel</a>
        </div>

        <div class="flex items-center gap-4">
            @auth
                <span class="hidden md:inline text-sm text-gray-500 font-medium">Halo, {{ $user?->siswa?->nama ?? $user?->guru?->nama ?? $user?->name ?? $user?->email }}</span>
                <a href="{{ $dashboardRoute }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200/50">
                    {{ $dashboardLabel }}
                </a>
            @else
                <button onclick="openLoginModal()" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200/50">
                    Login
                </button>
            @endauth
        </div>
    </nav>
</header>

<main id="beranda" class="pt-20">
    <!-- Hero Section -->
    <section class="relative bg-slate-900 overflow-hidden min-h-[500px] flex items-center pt-16 pb-36">
        <img src="/img/hero_students.png" alt="Siswa di Sekolah Bebas Perundungan" class="absolute inset-0 w-full h-full object-cover opacity-20" />
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-teal-900/50 to-blue-900/50"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white mb-6 leading-tight tracking-tight">
                Mengenali lebih awal, <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-teal-300">mendampingi lebih terarah</span>
            </h1>
            
            <p class="text-lg md:text-xl text-slate-300 max-w-3xl mx-auto mb-10 leading-relaxed">
                Platform cerdas untuk mendeteksi dini risiko perundungan, memetakan kondisi psikologis siswa, dan membangun lingkungan belajar yang aman & nyaman di sekolah.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#tentang" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-8 py-4 text-base font-bold text-white hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30 w-full sm:w-auto">
                    Pelajari Lebih Lanjut &rarr;
                </a>
            </div>
        </div>
    </section>

    <!-- Feature Cards (Overlapping) -->
    <section class="relative z-20 -mt-32 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Card 1 -->
            <div class="bg-white/60 backdrop-blur-lg border border-white/40 rounded-2xl p-8 shadow-xl shadow-gray-200/30 border-t-4 border-t-teal-500 hover:-translate-y-2 transition-transform duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 text-6xl font-black text-gray-50 opacity-50 group-hover:text-teal-50 transition-colors">01</div>
                <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-6 relative z-10">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 relative z-10">Deteksi Dini</h3>
                <p class="text-gray-500 text-sm leading-relaxed relative z-10">Kenali potensi dan indikasi perundungan sejak awal melalui data check-in harian emosional siswa.</p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white/60 backdrop-blur-lg border border-white/40 rounded-2xl p-8 shadow-xl shadow-gray-200/30 border-t-4 border-t-blue-500 hover:-translate-y-2 transition-transform duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 text-6xl font-black text-gray-50 opacity-50 group-hover:text-blue-50 transition-colors">02</div>
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 relative z-10">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 relative z-10">Aman & Anonim</h3>
                <p class="text-gray-500 text-sm leading-relaxed relative z-10">Siswa dapat melaporkan kejadian perundungan dengan aman, rahasia, dan terjamin kerahasiaannya.</p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white/60 backdrop-blur-lg border border-white/40 rounded-2xl p-8 shadow-xl shadow-gray-200/30 border-t-4 border-t-yellow-400 hover:-translate-y-2 transition-transform duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 text-6xl font-black text-gray-50 opacity-50 group-hover:text-yellow-50 transition-colors">03</div>
                <div class="w-14 h-14 bg-yellow-50 text-yellow-600 rounded-2xl flex items-center justify-center mb-6 relative z-10">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 relative z-10">Analitik Guru</h3>
                <p class="text-gray-500 text-sm leading-relaxed relative z-10">Dashboard analitik komprehensif untuk memantau kesejahteraan dan dinamika sosial di kelas.</p>
            </div>

            <!-- Card 4 -->
            <div class="bg-white/60 backdrop-blur-lg border border-white/40 rounded-2xl p-8 shadow-xl shadow-gray-200/30 border-t-4 border-t-teal-600 hover:-translate-y-2 transition-transform duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 text-6xl font-black text-gray-50 opacity-50 group-hover:text-teal-50 transition-colors">04</div>
                <div class="w-14 h-14 bg-teal-50 text-teal-700 rounded-2xl flex items-center justify-center mb-6 relative z-10">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 relative z-10">Dukungan Psikologi</h3>
                <p class="text-gray-500 text-sm leading-relaxed relative z-10">Integrasi dengan penanganan bimbingan konseling untuk intervensi yang tepat dan terukur.</p>
            </div>

        </div>
    </section>

    <!-- About Us Section -->
    <section id="tentang" class="py-24 bg-gradient-to-b from-slate-50 via-white to-blue-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                <!-- Image Collage -->
                <div class="relative">
                    <div class="grid grid-cols-2 gap-4">
                        <img src="/img/sma_students.png" alt="Siswa SMA" class="rounded-3xl object-cover h-[400px] w-full shadow-lg" />
                        <div class="grid grid-rows-2 gap-4">
                            <img src="/img/smp_students.png" alt="Siswa SMP Belajar" class="rounded-3xl object-cover h-[190px] w-full shadow-lg" />
                            <img src="/img/sd_students.png" alt="Interaksi Siswa SD" class="rounded-3xl object-cover h-[194px] w-full shadow-lg" />
                        </div>
                    </div>
                    
                    <!-- Floating Badge -->
                    <div class="absolute -bottom-8 -left-8 bg-blue-600 text-white p-6 rounded-3xl shadow-xl shadow-blue-500/30 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                        <div class="bg-white/20 p-3 rounded-2xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <span class="text-2xl font-black leading-none">100%</span><br/>
                            <span class="text-sm font-semibold opacity-90">Aman & Terpercaya</span>
                        </div>
                    </div>
                </div>

                <!-- Text Content -->
                <div>
                    <p class="text-blue-600 font-bold uppercase tracking-wider mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        TENTANG SAHABATKELAS
                    </p>
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6 leading-tight">Sistem Edukasi Kami Menginspirasi Lebih Banyak</h2>
                    <p class="text-gray-600 mb-8 leading-relaxed text-lg">
                        Membangun generasi yang sehat secara mental dan emosional adalah tanggung jawab bersama. Kami hadir dengan pendekatan proaktif menggunakan data untuk memastikan tidak ada siswa yang merasa sendirian dalam menghadapi masalahnya di sekolah.
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                        <!-- List item -->
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-gray-900 font-bold mb-1">Layanan Terintegrasi</h4>
                                <p class="text-sm text-gray-500 leading-relaxed">Terhubung langsung dengan BK dan Wali Kelas.</p>
                            </div>
                        </div>
                        <!-- List item -->
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-gray-900 font-bold mb-1">Pemantauan Harian</h4>
                                <p class="text-sm text-gray-500 leading-relaxed">Sistem check-in yang mudah dan cepat untuk siswa.</p>
                            </div>
                        </div>
                    </div>

                    <a href="#artikel" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-8 py-4 text-sm font-bold text-white hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                        Temukan Lebih Lanjut &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Banner -->
    <section id="statistik" class="py-20 bg-gradient-to-r from-teal-600 to-blue-700 text-white relative overflow-hidden mt-12">
        <!-- Decorative bg -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-white/20">
                
                <div class="text-center px-4">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl backdrop-blur flex items-center justify-center text-white">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                    </div>
                    <div class="text-4xl lg:text-5xl font-black mb-2">500+</div>
                    <div class="text-teal-100 font-semibold tracking-wide uppercase text-sm">Total Jurnal</div>
                </div>

                <div class="text-center px-4">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl backdrop-blur flex items-center justify-center text-white">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                    <div class="text-4xl lg:text-5xl font-black mb-2">1900+</div>
                    <div class="text-teal-100 font-semibold tracking-wide uppercase text-sm">Siswa Aktif</div>
                </div>

                <div class="text-center px-4">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl backdrop-blur flex items-center justify-center text-white">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <div class="text-4xl lg:text-5xl font-black mb-2">750+</div>
                    <div class="text-teal-100 font-semibold tracking-wide uppercase text-sm">Guru & BK</div>
                </div>

                <div class="text-center px-4">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl backdrop-blur flex items-center justify-center text-white">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        </div>
                    </div>
                    <div class="text-4xl lg:text-5xl font-black mb-2">30+</div>
                    <div class="text-teal-100 font-semibold tracking-wide uppercase text-sm">Sekolah Mitra</div>
                </div>

            </div>
        </div>
    </section>

    <!-- News / Articles Section -->
    <section id="artikel" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-16">
            <p class="text-orange-500 font-bold uppercase tracking-wider mb-2">INFORMASI & ARTIKEL</p>
            <h2 class="text-4xl font-black text-gray-900 mb-4">Mari Simak Kabar Terkini</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Berita, tips, dan panduan edukasi untuk mencegah perundungan serta menjaga kesejahteraan mental peserta didik.</p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Article 1 -->
                <div class="bg-white rounded-3xl overflow-hidden shadow-md shadow-gray-200/50 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 flex flex-col group">
                    <div class="relative overflow-hidden h-56">
                        <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?q=80&w=2070&auto=format&fit=crop" alt="Edukasi" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                        <div class="absolute top-4 left-4 bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg">Edukasi</div>
                    </div>
                    <div class="p-8 flex flex-col flex-grow">
                        <div class="flex items-center gap-4 text-xs text-gray-400 font-medium mb-4">
                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 18 Jul 2026</span>
                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Admin</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors leading-snug">Cara Mengenali Tanda Perundungan Terselubung di Kelas</h3>
                        <p class="text-gray-500 text-sm mb-6 line-clamp-3 leading-relaxed">Tidak semua perundungan bersifat fisik. Perundungan verbal dan pengucilan seringkali tidak terlihat oleh guru. Kenali gejalanya sejak dini...</p>
                        <a href="#" class="mt-auto inline-flex items-center text-blue-600 font-bold text-sm hover:text-blue-800 transition-colors">
                            Baca Selengkapnya <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Article 2 -->
                <div class="bg-white rounded-3xl overflow-hidden shadow-md shadow-gray-200/50 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 flex flex-col group">
                    <div class="relative overflow-hidden h-56">
                        <img src="https://images.unsplash.com/photo-1571260899304-425dea4cf863?q=80&w=2072&auto=format&fit=crop" alt="Kesehatan Mental" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                        <div class="absolute top-4 left-4 bg-teal-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg">Psikologi</div>
                    </div>
                    <div class="p-8 flex flex-col flex-grow">
                        <div class="flex items-center gap-4 text-xs text-gray-400 font-medium mb-4">
                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 15 Jul 2026</span>
                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Tim BK</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors leading-snug">Pentingnya Ruang Aman untuk Pemulihan Emosional</h3>
                        <p class="text-gray-500 text-sm mb-6 line-clamp-3 leading-relaxed">Siswa membutuhkan lingkungan di mana mereka merasa diterima dan tidak dihakimi. Bagaimana sekolah bisa menjadi ruang aman tersebut?</p>
                        <a href="#" class="mt-auto inline-flex items-center text-blue-600 font-bold text-sm hover:text-blue-800 transition-colors">
                            Baca Selengkapnya <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Article 3 -->
                <div class="bg-white rounded-3xl overflow-hidden shadow-md shadow-gray-200/50 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 flex flex-col group">
                    <div class="relative overflow-hidden h-56">
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop" alt="Kolaborasi" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                        <div class="absolute top-4 left-4 bg-blue-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg">Tips</div>
                    </div>
                    <div class="p-8 flex flex-col flex-grow">
                        <div class="flex items-center gap-4 text-xs text-gray-400 font-medium mb-4">
                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 10 Jul 2026</span>
                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Edukasi</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors leading-snug">Membangun Empati di Antara Peserta Didik Sejak Dini</h3>
                        <p class="text-gray-500 text-sm mb-6 line-clamp-3 leading-relaxed">Cara efektif mencegah perundungan adalah dengan menanamkan empati. Berbagai kegiatan kolaboratif di kelas dapat mewujudkannya.</p>
                        <a href="#" class="mt-auto inline-flex items-center text-blue-600 font-bold text-sm hover:text-blue-800 transition-colors">
                            Baca Selengkapnya <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>

<footer class="bg-slate-900 text-slate-300 py-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-4 justify-center md:justify-start">
                    <div class="w-10 h-10 bg-teal-500 rounded-xl flex items-center justify-center text-white font-black text-xl">S</div>
                    <span class="text-xl font-black text-white">SahabatKelas<span class="text-teal-500">.</span></span>
                </div>
                <p class="text-slate-400 mb-6 max-w-sm mx-auto md:mx-0">Menjadi teman setia dalam mendeteksi dan mencegah perundungan, demi masa depan pendidikan yang lebih baik.</p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4">Tautan Cepat</h4>
                <ul class="space-y-2">
                    <li><a href="#beranda" class="hover:text-teal-400 transition-colors">Beranda</a></li>
                    <li><a href="#tentang" class="hover:text-teal-400 transition-colors">Tentang Kami</a></li>
                    <li><a href="#statistik" class="hover:text-teal-400 transition-colors">Statistik</a></li>
                    <li><a href="#artikel" class="hover:text-teal-400 transition-colors">Artikel & Berita</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4">Hubungi Kami</h4>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2 justify-center md:justify-start"><svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> info@sahabatkelas.id</li>
                    <li class="flex items-center gap-2 justify-center md:justify-start"><svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> 0812-3456-7890</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-8 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} SahabatKelas. Hak cipta dilindungi.
        </div>
    </div>
</footer>

<!-- Login Modal -->
<div id="login-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 transition-opacity backdrop-blur-sm" onclick="closeLoginModal()"></div>

    <div class="relative transform overflow-hidden rounded-3xl bg-white p-8 text-left shadow-2xl transition-all w-full max-w-md border border-teal-100">
        <button type="button" onclick="closeLoginModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 bg-gray-50 hover:bg-gray-100 rounded-full p-2 transition-colors">
            <span class="sr-only">Close</span>
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        <div class="text-center mb-8 mt-2">
            <img src="/img/logo.png" alt="Logo SahabatKelas" class="mx-auto h-16 w-auto mb-4 drop-shadow-md">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">SahabatKelas</h2>
            <p class="text-sm text-gray-500">Silakan masuk menggunakan akun yang telah diberikan oleh sekolah.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-3 rounded-xl text-sm mb-6 text-center border border-red-100 flex items-center justify-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all">
            </div>
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl mt-4 transition-all shadow-md shadow-blue-200 active:scale-[0.98]">
                Masuk ke Akun
            </button>
        </form>
    </div>
</div>

<script>
    // Simple script to add shadow to navbar on scroll
    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        if (window.scrollY > 20) {
            header.classList.add('shadow-md', 'bg-white/70', 'backdrop-blur-lg');
            header.classList.remove('bg-white/40', 'backdrop-blur-md');
        } else {
            header.classList.remove('shadow-md', 'bg-white/70', 'backdrop-blur-lg');
            header.classList.add('bg-white/40', 'backdrop-blur-md');
        }
    });

    // Login Modal Logic
    const modal = document.getElementById('login-modal');
    function openLoginModal() {
        if(modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }
    function closeLoginModal() {
        if(modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    // Auto-open modal on the login page so the form is visible right away
    openLoginModal();
    
    @if($errors->any())
        openLoginModal();
    @endif
</script>

</body>
</html>