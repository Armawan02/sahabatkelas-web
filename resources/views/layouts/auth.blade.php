<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    {{-- Judul diambil dari view yang menggunakan layout ini --}}
    <title>@yield('title', 'SahabatKelas')</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-teal-50 p-4 font-sans flex items-center justify-center">

    {{-- Konten halaman login --}}
    @yield('content')

</body>
</html>