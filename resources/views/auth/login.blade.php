<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - SahabatKelas</title>
    <link rel="icon" type="image/png" href="/img/favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hero-grid {
            background-image:
                linear-gradient(rgba(20, 184, 166, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(20, 184, 166, 0.08) 1px, transparent 1px);
            background-size: 32px 32px;
        }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased min-h-screen flex flex-col relative">

    {{-- Navbar --}}
    <header class="sticky top-0 z-40 border-b border-gray-100 bg-white/90 backdrop-blur-xl">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/img/logo.png" alt="Logo" class="h-8 w-auto">
                <div>
                    <p class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-teal-500 tracking-tight">SahabatKelas</p>
                </div>
            </div>
            <div>
                <button onclick="openLoginModal()" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors shadow-sm shadow-blue-200">
                    Masuk
                </button>
            </div>
        </nav>
    </header>

    {{-- Hero Section --}}
    <main class="flex-grow flex items-center relative overflow-hidden bg-gradient-to-b from-blue-50/80 via-white to-white hero-grid">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 text-center flex flex-col items-center relative z-10">
            <div>
                <img src="/img/logo.png" alt="Logo SahabatKelas" class="h-32 w-32 md:h-40 md:w-40 object-contain mx-auto mb-8 drop-shadow-xl">
                <h1 class="text-4xl sm:text-5xl lg:text-5xl font-black tracking-tight text-gray-900 leading-tight">
                    Mengenali lebih awal,<br>
                    mendampingi lebih terarah.
                </h1>
                <p class="mt-6 text-lg leading-relaxed text-gray-600">
                    SahabatKelas hadir untuk mendukung sekolah, guru, dan siswa dalam mewujudkan ruang belajar yang aman, suportif, dan bebas perundungan.
                </p>
                <div class="mt-8">
                    <button onclick="openLoginModal()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-8 py-4 text-base font-bold text-white hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                        Masuk ke Platform
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 bg-gray-50 mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">© {{ now()->year }} SahabatKelas. Dikembangkan untuk inovasi pendidikan.</p>
        </div>
    </footer>

    {{-- Login Modal --}}
    <div id="login-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background backdrop -->
        <div class="fixed inset-0 bg-gray-900/60 transition-opacity backdrop-blur-sm" onclick="closeLoginModal()"></div>

        <!-- Modal panel -->
        <div class="relative transform overflow-hidden rounded-3xl bg-white p-8 text-left shadow-2xl transition-all w-full max-w-md border border-teal-100">
            <!-- Close button -->
            <button type="button" onclick="closeLoginModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 bg-gray-50 hover:bg-gray-100 rounded-full p-2 transition-colors">
                <span class="sr-only">Close</span>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="text-center mb-8 mt-2">
                <img src="/img/logo.png" alt="Logo SahabatKelas" class="mx-auto h-16 w-auto mb-4">
                <h2 class="text-3xl font-bold text-blue-700 mb-2">SahabatKelas</h2>
                <p class="text-sm text-gray-500">Silakan masuk menggunakan akun yang telah diberikan oleh sekolah.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-3 rounded-xl text-sm mb-6 text-center border border-red-100 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi</label>
                    <input type="password" id="password" name="password" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl mt-4 transition-all shadow-md shadow-blue-200 active:scale-[0.98]">
                    Masuk ke Akun
                </button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('login-modal');
        function openLoginModal() {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeLoginModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        @if($errors->any())
            openLoginModal();
        @endif
    </script>
</body>
</html>