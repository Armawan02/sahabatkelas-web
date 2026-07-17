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
    <div
        class="max-w-7xl mx-auto px-4 py-3
               flex justify-between items-center gap-4"
    >
        {{-- Logo --}}
        <a
            href="{{ $homeUrl }}"
            class="text-xl font-bold text-teal-700
                   flex items-center gap-2 shrink-0"
        >
            <span
                class="w-9 h-9 rounded-xl bg-teal-600
                       text-white flex items-center justify-center"
            >
                S
            </span>

            <span class="hidden sm:inline">
                SahabatKelas
            </span>
        </a>

        {{-- Informasi pengguna --}}
        @auth
            <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                <div class="hidden md:block text-right min-w-0">
                    <p class="text-sm font-semibold text-gray-700 truncate">
                        {{ $namaPengguna }}
                    </p>

                    <p class="text-xs text-gray-400 capitalize">
                        {{ $user->role }}
                    </p>
                </div>

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="inline"
                >
                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center
                               text-sm font-medium text-red-600
                               hover:text-red-700 hover:bg-red-50
                               px-3 py-2 rounded-lg transition-colors"
                    >
                        Keluar
                    </button>
                </form>
            </div>
        @endauth
    </div>

    {{-- Navigasi role --}}
    @auth
        <div class="border-t border-gray-100 bg-gray-50/70">
            <div
                class="max-w-7xl mx-auto px-4 py-2
                       flex items-center gap-6 overflow-x-auto
                       text-sm font-medium whitespace-nowrap
                       hide-scrollbar"
            >

                {{-- Menu Admin --}}
                @if ($user->role === 'admin')
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="py-1.5 transition-colors
                            {{ request()->routeIs('admin.dashboard')
                                ? $kelasAktif
                                : $kelasTidakAktif }}"
                    >
                        Dashboard
                    </a>

                    @if (Route::has('admin.users.index'))
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="py-1.5 transition-colors
                                {{ request()->routeIs('admin.users.*')
                                    ? $kelasAktif
                                    : $kelasTidakAktif }}"
                        >
                            Kelola Akun
                        </a>
                    @endif

                    @if (Route::has('admin.kelas.index'))
                        <a
                            href="{{ route('admin.kelas.index') }}"
                            class="py-1.5 transition-colors
                                {{ request()->routeIs('admin.kelas.*')
                                    ? $kelasAktif
                                    : $kelasTidakAktif }}"
                        >
                            Data Kelas
                        </a>
                    @endif

                    @if (Route::has('admin.siswa.index'))
                        <a
                            href="{{ route('admin.siswa.index') }}"
                            class="py-1.5 transition-colors
                                {{ request()->routeIs('admin.siswa.*')
                                    ? $kelasAktif
                                    : $kelasTidakAktif }}"
                        >
                            Data Siswa
                        </a>
                    @endif

                    @if (Route::has('admin.guru.index'))
                        <a
                            href="{{ route('admin.guru.index') }}"
                            class="py-1.5 transition-colors
                                {{ request()->routeIs('admin.guru.*')
                                    ? $kelasAktif
                                    : $kelasTidakAktif }}"
                        >
                            Data Guru
                        </a>
                    @endif

                {{-- Menu Guru --}}
                @elseif ($user->role === 'guru')
                    <a
                        href="{{ route('guru.dashboard') }}"
                        class="py-1.5 transition-colors
                            {{ request()->routeIs('guru.dashboard')
                                ? $kelasAktif
                                : $kelasTidakAktif }}"
                    >
                        Dashboard
                    </a>

                    <a
                        href="{{ route('guru.heatmap') }}"
                        class="py-1.5 transition-colors
                            {{ request()->routeIs(
                                'guru.heatmap',
                                'guru.siswa.detail'
                            )
                                ? $kelasAktif
                                : $kelasTidakAktif }}"
                    >
                        Heatmap Kelas
                    </a>

                    <a
                        href="{{ route('guru.tindak-lanjut.index') }}"
                        class="py-1.5 transition-colors
                            {{ request()->routeIs(
                                'guru.tindak-lanjut.*',
                                'guru.monitoring.*'
                            )
                                ? $kelasAktif
                                : $kelasTidakAktif }}"
                    >
                        Tindak Lanjut
                    </a>

                {{-- Menu Siswa --}}
                @elseif ($user->role === 'siswa')
                    <a
                        href="{{ route('siswa.beranda') }}"
                        class="py-1.5 transition-colors
                            {{ request()->routeIs('siswa.beranda')
                                ? $kelasAktif
                                : $kelasTidakAktif }}"
                    >
                        Beranda
                    </a>

                    <a
                        href="{{ route('siswa.checkin.create') }}"
                        class="py-1.5 transition-colors
                            {{ request()->routeIs('siswa.checkin.*')
                                ? $kelasAktif
                                : $kelasTidakAktif }}"
                    >
                        Check-in
                    </a>

                    <a
                        href="{{ route('siswa.report.create') }}"
                        class="py-1.5 transition-colors
                            {{ request()->routeIs('siswa.report.*')
                                ? $kelasAktif
                                : $kelasTidakAktif }}"
                    >
                        Safe Report
                    </a>
                @endif
            </div>
        </div>
    @endauth
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