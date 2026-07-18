@php
    $user = auth()->user();

    /*
     * Nama yang ditampilkan menyesuaikan role.
     * Null-safe operator mencegah error ketika
     * relasi siswa atau guru tidak tersedia.
     */
    $namaPengguna =
        $user?->siswa?->nama
        ?? $user?->guru?->nama
        ?? $user?->name
        ?? $user?->email
        ?? 'Pengguna';

    /*
     * Tautan logo menyesuaikan role pengguna.
     */
    $homeUrl = match ($user?->role) {
        'admin' => route('admin.dashboard'),
        'guru' => route('guru.dashboard'),
        'siswa' => route('siswa.beranda'),
        default => route('login'),
    };

    $kelasAktif =
        'text-teal-600 border-b-2 border-teal-600';

    $kelasTidakAktif =
        'text-gray-600 border-b-2 border-transparent hover:text-teal-600';
@endphp

<nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">

    {{-- Bagian atas --}}
    <div class="w-full mx-auto px-4 py-3 flex justify-between items-center gap-4">
        
        {{-- Hamburger Menu & Logo (Hanya Mobile) --}}
        <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-teal-700 focus:outline-none p-2 rounded-md hover:bg-teal-50 shrink-0 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="lg:hidden flex items-center gap-2">
                <img src="/img/logo.png" alt="Logo" class="w-8 h-8 object-contain">
                <span class="font-bold text-gray-800 text-lg tracking-tight">SahabatKelas</span>
            </div>
        </div>

        {{-- Informasi pengguna (Profil Dropdown) --}}
        @auth
            <div class="relative inline-block text-left">
                <button onclick="toggleProfileDropdown()" type="button" class="flex items-center gap-3 focus:outline-none rounded-full pr-3 pl-1 py-1 hover:bg-gray-100 transition-colors">
                    <img class="h-10 w-10 rounded-full object-cover shadow-sm border border-gray-200 bg-white" src="https://ui-avatars.com/api/?name={{ urlencode($namaPengguna) }}&background=0d9488&color=fff&bold=true" alt="Avatar">
                    
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-bold text-gray-800 leading-tight">{{ $namaPengguna }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ $user->role }}</p>
                    </div>
                    
                    <svg class="w-4 h-4 text-gray-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-xl border border-gray-100 divide-y divide-gray-100 focus:outline-none z-50 transition-all origin-top-right transform scale-95 opacity-0 duration-150 ease-out">
                    <div class="px-4 py-3">
                        <p class="text-xs text-gray-500">Masuk sebagai</p>
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $user->email }}</p>
                    </div>
                    <div class="py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar dari Sistem
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</nav>

<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profile-dropdown');
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            setTimeout(() => {
                dropdown.classList.remove('scale-95', 'opacity-0');
                dropdown.classList.add('scale-100', 'opacity-100');
            }, 10);
        } else {
            dropdown.classList.remove('scale-100', 'opacity-100');
            dropdown.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 150);
        }
    }

    // Menutup dropdown jika klik di luar area profile
    window.addEventListener('click', function(e) {
        const dropdown = document.getElementById('profile-dropdown');
        const profileContainer = document.querySelector('.relative.inline-block.text-left');
        
        if (profileContainer && !profileContainer.contains(e.target)) {
            if (dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('scale-100', 'opacity-100');
                dropdown.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 150);
            }
        }
    });
</script>