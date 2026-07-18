@php
    $user = auth()->user();
    
    // Updated color scheme for dark sidebar (bg-teal-800)
    $kelasAktif = 'bg-teal-900 text-white font-semibold shadow-inner border-l-4 border-teal-400';
    $kelasTidakAktif = 'text-teal-100 hover:bg-teal-700 hover:text-white font-medium border-l-4 border-transparent';
    $iconAktif = 'text-teal-300';
    $iconTidakAktif = 'text-teal-300 group-hover:text-white';
@endphp

<!-- Overlay untuk mobile -->
<div 
    id="sidebar-overlay" 
    class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden hidden transition-opacity duration-300 opacity-0"
    onclick="toggleSidebar()"
></div>

<!-- Sidebar -->
<aside 
    id="sidebar" 
    class="fixed inset-y-0 left-0 z-50 w-60 lg:w-64 bg-teal-800 border-r border-teal-900 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 flex flex-col h-screen text-white shadow-xl transition-all duration-300"
>
    <!-- Header Sidebar -->
    <div id="sidebar-header" class="h-16 flex items-center border-b border-teal-700/50 flex-shrink-0 relative w-full transition-all duration-300">
        
        <!-- Expanded Header Content (Logo + X Button) -->
        <div class="sidebar-expanded-content flex items-center justify-between w-full px-6 transition-opacity duration-300">
            <div class="flex items-center gap-3">
                <img src="/img/logo.png" alt="Logo" class="w-8 h-8 object-contain bg-white rounded-full p-0.5">
                <span class="text-lg font-bold text-white whitespace-nowrap">SahabatKelas</span>
            </div>
            
            <!-- Tombol Close (X) untuk Desktop & Mobile -->
            <button onclick="toggleSidebar()" class="text-teal-200 hover:text-white focus:outline-none p-1 rounded-md hover:bg-teal-700 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Collapsed Header Content (Hamburger Icon Only) -->
        <div class="sidebar-collapsed-content absolute inset-0 flex items-center justify-center transition-opacity duration-300">
            <button onclick="toggleSidebar()" class="text-teal-200 hover:text-white focus:outline-none p-2 rounded-md hover:bg-teal-700 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Menu Scrollable -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 scrollbar-thin scrollbar-thumb-teal-600">
        
        {{-- Menu Admin --}}
        @if ($user->role === 'admin')
            <p class="sidebar-group-title px-3 text-xs font-semibold text-teal-300 uppercase tracking-wider mb-2 transition-opacity duration-300">Menu Utama</p>
            
            <a href="{{ route('admin.dashboard') }}" title="Dashboard" class="sidebar-menu-item group flex items-center px-3 py-2.5 text-sm rounded-r-xl rounded-l-sm transition-all {{ request()->routeIs('admin.dashboard') ? $kelasAktif : $kelasTidakAktif }}">
                <svg class="sidebar-icon mr-3 w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? $iconAktif : $iconTidakAktif }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span class="sidebar-text transition-opacity duration-300 whitespace-nowrap">Dashboard</span>
            </a>

            @if (Route::has('admin.users.index'))
                <a href="{{ route('admin.users.index') }}" title="Kelola Akun" class="sidebar-menu-item group flex items-center px-3 py-2.5 text-sm rounded-r-xl rounded-l-sm transition-all {{ request()->routeIs('admin.users.*') ? $kelasAktif : $kelasTidakAktif }}">
                    <svg class="sidebar-icon mr-3 w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.users.*') ? $iconAktif : $iconTidakAktif }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="sidebar-text transition-opacity duration-300 whitespace-nowrap">Kelola Akun</span>
                </a>
            @endif

            <p class="sidebar-group-title px-3 text-xs font-semibold text-teal-300 uppercase tracking-wider mb-2 mt-6 transition-opacity duration-300">Data Master</p>
            
            @if (Route::has('admin.kelas.index'))
                <a href="{{ route('admin.kelas.index') }}" title="Data Kelas" class="sidebar-menu-item group flex items-center px-3 py-2.5 text-sm rounded-r-xl rounded-l-sm transition-all {{ request()->routeIs('admin.kelas.*') ? $kelasAktif : $kelasTidakAktif }}">
                    <svg class="sidebar-icon mr-3 w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.kelas.*') ? $iconAktif : $iconTidakAktif }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="sidebar-text transition-opacity duration-300 whitespace-nowrap">Data Kelas</span>
                </a>
            @endif

            @if (Route::has('admin.siswa.index'))
                <a href="{{ route('admin.siswa.index') }}" title="Data Siswa" class="sidebar-menu-item group flex items-center px-3 py-2.5 text-sm rounded-r-xl rounded-l-sm transition-all {{ request()->routeIs('admin.siswa.*') ? $kelasAktif : $kelasTidakAktif }}">
                    <svg class="sidebar-icon mr-3 w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.siswa.*') ? $iconAktif : $iconTidakAktif }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                    <span class="sidebar-text transition-opacity duration-300 whitespace-nowrap">Data Siswa</span>
                </a>
            @endif

            @if (Route::has('admin.guru.index'))
                <a href="{{ route('admin.guru.index') }}" title="Data Guru" class="sidebar-menu-item group flex items-center px-3 py-2.5 text-sm rounded-r-xl rounded-l-sm transition-all {{ request()->routeIs('admin.guru.*') ? $kelasAktif : $kelasTidakAktif }}">
                    <svg class="sidebar-icon mr-3 w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.guru.*') ? $iconAktif : $iconTidakAktif }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="sidebar-text transition-opacity duration-300 whitespace-nowrap">Data Guru</span>
                </a>
            @endif

        {{-- Menu Guru --}}
        @elseif ($user->role === 'guru')
            <p class="sidebar-group-title px-3 text-xs font-semibold text-teal-300 uppercase tracking-wider mb-2 transition-opacity duration-300">Menu Utama</p>
            
            <a href="{{ route('guru.dashboard') }}" title="Dashboard" class="sidebar-menu-item group flex items-center px-3 py-2.5 text-sm rounded-r-xl rounded-l-sm transition-all {{ request()->routeIs('guru.dashboard') ? $kelasAktif : $kelasTidakAktif }}">
                <svg class="sidebar-icon mr-3 w-5 h-5 flex-shrink-0 {{ request()->routeIs('guru.dashboard') ? $iconAktif : $iconTidakAktif }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span class="sidebar-text transition-opacity duration-300 whitespace-nowrap">Dashboard</span>
            </a>

            <a href="{{ route('guru.heatmap') }}" title="Heatmap Kelas" class="sidebar-menu-item group flex items-center px-3 py-2.5 text-sm rounded-r-xl rounded-l-sm transition-all {{ request()->routeIs('guru.heatmap', 'guru.siswa.detail') ? $kelasAktif : $kelasTidakAktif }}">
                <svg class="sidebar-icon mr-3 w-5 h-5 flex-shrink-0 {{ request()->routeIs('guru.heatmap', 'guru.siswa.detail') ? $iconAktif : $iconTidakAktif }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="sidebar-text transition-opacity duration-300 whitespace-nowrap">Heatmap Kelas</span>
            </a>

            <a href="{{ route('guru.tindak-lanjut.index') }}" title="Tindak Lanjut" class="sidebar-menu-item group flex items-center px-3 py-2.5 text-sm rounded-r-xl rounded-l-sm transition-all {{ request()->routeIs('guru.tindak-lanjut.*', 'guru.monitoring.*') ? $kelasAktif : $kelasTidakAktif }}">
                <svg class="sidebar-icon mr-3 w-5 h-5 flex-shrink-0 {{ request()->routeIs('guru.tindak-lanjut.*', 'guru.monitoring.*') ? $iconAktif : $iconTidakAktif }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="sidebar-text transition-opacity duration-300 whitespace-nowrap">Tindak Lanjut</span>
            </a>

        {{-- Menu Siswa --}}
        @elseif ($user->role === 'siswa')
            <p class="sidebar-group-title px-3 text-xs font-semibold text-teal-300 uppercase tracking-wider mb-2 transition-opacity duration-300">Menu Utama</p>
            
            <a href="{{ route('siswa.beranda') }}" title="Beranda" class="sidebar-menu-item group flex items-center px-3 py-2.5 text-sm rounded-r-xl rounded-l-sm transition-all {{ request()->routeIs('siswa.beranda') ? $kelasAktif : $kelasTidakAktif }}">
                <svg class="sidebar-icon mr-3 w-5 h-5 flex-shrink-0 {{ request()->routeIs('siswa.beranda') ? $iconAktif : $iconTidakAktif }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="sidebar-text transition-opacity duration-300 whitespace-nowrap">Beranda</span>
            </a>

            <a href="{{ route('siswa.checkin.create') }}" title="Check-in Harian" class="sidebar-menu-item group flex items-center px-3 py-2.5 text-sm rounded-r-xl rounded-l-sm transition-all {{ request()->routeIs('siswa.checkin.*') ? $kelasAktif : $kelasTidakAktif }}">
                <svg class="sidebar-icon mr-3 w-5 h-5 flex-shrink-0 {{ request()->routeIs('siswa.checkin.*') ? $iconAktif : $iconTidakAktif }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="sidebar-text transition-opacity duration-300 whitespace-nowrap">Check-in Harian</span>
            </a>

            <a href="{{ route('siswa.report.create') }}" title="Safe Report" class="sidebar-menu-item group flex items-center px-3 py-2.5 text-sm rounded-r-xl rounded-l-sm transition-all {{ request()->routeIs('siswa.report.*') ? $kelasAktif : $kelasTidakAktif }}">
                <svg class="sidebar-icon mr-3 w-5 h-5 flex-shrink-0 {{ request()->routeIs('siswa.report.*') ? $iconAktif : $iconTidakAktif }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="sidebar-text transition-opacity duration-300 whitespace-nowrap">Safe Report</span>
            </a>
        @endif
        
    </div>
    
    <!-- Footer Sidebar -->
    <div id="sidebar-footer-box" class="p-4 border-t border-teal-700/50 transition-opacity duration-300 flex flex-col gap-3">
        
        <!-- Dark Mode Toggle -->
        <button onclick="toggleDarkMode()" class="w-full flex items-center justify-between bg-teal-900/50 hover:bg-teal-900 rounded-xl p-3 transition-colors border border-teal-700/50 group">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 group-hover:text-white transition-colors" id="theme-icon-container">
                    <!-- Icon Light (Sun) by default -->
                    <svg id="theme-icon-light" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <!-- Icon Dark (Moon) by default -->
                    <svg id="theme-icon-dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                </div>
                <span class="text-sm font-medium text-teal-100 group-hover:text-white transition-colors" id="theme-text">Mode Gelap</span>
            </div>
            
            <!-- Switch UI -->
            <div class="relative inline-flex h-5 w-9 items-center rounded-full bg-teal-700 transition-colors" id="theme-switch-bg">
                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform translate-x-1" id="theme-switch-knob"></span>
            </div>
        </button>

        <div class="bg-teal-900 rounded-xl p-4 flex flex-col items-center text-center shadow-inner">
            <p class="text-xs font-semibold text-teal-200">Butuh Bantuan?</p>
            <p class="text-[10px] text-teal-400 mt-1">Hubungi Administrator Sekolah Anda</p>
        </div>
    </div>
</aside>

<script>
    function toggleDarkMode() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        
        const iconLight = document.getElementById('theme-icon-light');
        const iconDark = document.getElementById('theme-icon-dark');
        const themeText = document.getElementById('theme-text');
        const switchBg = document.getElementById('theme-switch-bg');
        const switchKnob = document.getElementById('theme-switch-knob');

        if (isDark) {
            // Switch to Light
            html.classList.remove('dark');
            localStorage.theme = 'light';
            
            iconLight.classList.add('hidden');
            iconDark.classList.remove('hidden');
            themeText.textContent = 'Mode Gelap';
            
            switchBg.classList.replace('bg-blue-500', 'bg-teal-700');
            switchKnob.classList.replace('translate-x-4', 'translate-x-1');
        } else {
            // Switch to Dark
            html.classList.add('dark');
            localStorage.theme = 'dark';
            
            iconDark.classList.add('hidden');
            iconLight.classList.remove('hidden');
            themeText.textContent = 'Mode Terang';
            
            switchBg.classList.replace('bg-teal-700', 'bg-blue-500');
            switchKnob.classList.replace('translate-x-1', 'translate-x-4');
        }
    }

    // Check preference on load
    window.addEventListener('DOMContentLoaded', () => {
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            
            const iconLight = document.getElementById('theme-icon-light');
            const iconDark = document.getElementById('theme-icon-dark');
            const themeText = document.getElementById('theme-text');
            const switchBg = document.getElementById('theme-switch-bg');
            const switchKnob = document.getElementById('theme-switch-knob');
            
            if(iconLight) iconLight.classList.remove('hidden');
            if(iconDark) iconDark.classList.add('hidden');
            if(themeText) themeText.textContent = 'Mode Terang';
            if(switchBg) switchBg.classList.replace('bg-teal-700', 'bg-blue-500');
            if(switchKnob) switchKnob.classList.replace('translate-x-1', 'translate-x-4');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });
</script>
