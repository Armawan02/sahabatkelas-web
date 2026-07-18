@extends('layouts.auth')

@section('title', 'Masuk - SahabatKelas')

@section('content')
<div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-sm border border-teal-100">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-teal-700 mb-2">SahabatKelas</h1>
        <p class="text-sm text-gray-500">Silakan masuk menggunakan akun yang telah diberikan oleh sekolah.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-6 text-center border border-red-100">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="/login" method="POST" class="space-y-5">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
            <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none">
        </div>
        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 rounded-lg mt-2">Masuk</button>
    </form>
</div>
@endsection