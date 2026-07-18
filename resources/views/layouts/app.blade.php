<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SahabatKelas')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <style>
        /* Smooth transition for sidebar width */
        #sidebar {
            transition: width 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        
        /* Desktop Collapsed State (Hanya di Laptop) */
        @media (min-width: 1024px) {
            html.sidebar-collapsed #sidebar {
                width: 5rem; /* w-20 */
            }
            html.sidebar-collapsed .sidebar-text,
            html.sidebar-collapsed .sidebar-group-title {
                display: none;
                opacity: 0;
            }
            html.sidebar-collapsed .sidebar-expanded-content {
                opacity: 0;
                pointer-events: none;
            }
            html.sidebar-collapsed .sidebar-collapsed-content {
                opacity: 1;
                pointer-events: auto;
            }
            html:not(.sidebar-collapsed) .sidebar-collapsed-content {
                opacity: 0;
                pointer-events: none;
            }
            html.sidebar-collapsed #sidebar-header {
                padding-left: 0;
                padding-right: 0;
            }
            html.sidebar-collapsed .sidebar-menu-item {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }
            html.sidebar-collapsed .sidebar-icon {
                margin-right: 0;
            }
            html.sidebar-collapsed #sidebar-footer-box {
                display: none;
            }
        }

        /* Global Dark Mode Overrides */
        html.dark body {
            background-color: #0f172a; /* slate-900 */
            color: #f8fafc; /* slate-50 */
        }
        html.dark main {
            background-color: #0f172a !important; /* slate-900 */
        }
        /* Ubah semua elemen kartu/background putih menjadi slate gelap */
        html.dark .bg-white {
            background-color: #1e293b !important; /* slate-800 */
            border-color: #334155 !important; /* slate-700 */
        }
        html.dark .bg-gray-50 {
            background-color: #0f172a !important; /* slate-900 */
        }
        html.dark .bg-gray-100 {
            background-color: #334155 !important; /* slate-700 */
        }
        /* Sesuaikan teks abu-abu/hitam menjadi terang */
        html.dark .text-gray-800, html.dark .text-gray-900, html.dark .text-gray-700 {
            color: #f8fafc !important; /* slate-50 */
        }
        html.dark .text-gray-500, html.dark .text-gray-600, html.dark .text-gray-400 {
            color: #94a3b8 !important; /* slate-400 */
        }
        /* Sesuaikan border */
        html.dark .border-gray-100, html.dark .border-gray-200 {
            border-color: #334155 !important; /* slate-700 */
        }
        html.dark .divide-gray-100 > :not([hidden]) ~ :not([hidden]) {
            border-color: #334155 !important;
        }
        html.dark nav {
            background-color: #1e293b !important;
            border-bottom-color: #334155 !important;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans flex h-screen overflow-hidden">
    
    <!-- Script untuk inisialisasi state agar tidak berkedip -->
    <script>
        const storedState = localStorage.getItem('sidebarExpanded');
        if (storedState === 'false') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>

    <!-- Memanggil potongan sidebar -->
    @auth
        @include('layouts.partials.sidebar')
    @endauth

    <div class="flex-1 flex flex-col h-screen overflow-hidden w-full relative">
        <!-- Memanggil potongan navbar -->
        @include('layouts.partials.navbar')

        <!-- Area utama untuk injeksi konten halaman -->
        <main class="flex-1 overflow-y-auto bg-gray-50/50 p-4 md:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const isDesktop = window.innerWidth >= 1024;
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (isDesktop) {
                // Desktop: Toggle collapsed class (mini sidebar)
                const html = document.documentElement;
                html.classList.toggle('sidebar-collapsed');
                
                // Simpan state di localStorage
                const isExpanded = !html.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebarExpanded', isExpanded);
            } else {
                // Mobile: Toggle off-canvas (muncul dari kiri)
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                    setTimeout(() => overlay.classList.remove('opacity-0'), 10);
                } else {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('opacity-0');
                    setTimeout(() => overlay.classList.add('hidden'), 300);
                }
            }
        }
    </script>
</body>
</html>