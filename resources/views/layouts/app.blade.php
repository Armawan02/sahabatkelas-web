<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SahabatKelas')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

    <!-- Memanggil potongan navbar -->
    @include('layouts.partials.navbar')

    <main class="max-w-7xl mx-auto p-4 mt-6">
        <!-- Area utama untuk injeksi konten halaman -->
        @yield('content')
    </main>

</body>
</html>