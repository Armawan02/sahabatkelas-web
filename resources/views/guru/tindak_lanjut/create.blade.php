@extends('layouts.app')

@section('title', 'Buat Tindak Lanjut - SahabatKelas')

@section('content')
@php
    $analisis = $rekomendasi->analisisResiko;
    $siswa = $analisis->siswa;
@endphp

<div class="max-w-4xl mx-auto mb-10">

    {{-- Header --}}
    <div class="mb-6">
        <a
            href="{{ route(
                'guru.siswa.detail',
                $siswa->id_siswa
            ) }}"
            class="text-sm font-medium text-teal-600 hover:text-teal-700"
        >
            ← Kembali ke detail siswa
        </a>

        <h1 class="text-2xl font-bold text-gray-800 mt-3">
            Buat Tindak Lanjut
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Catat tindakan pendampingan berdasarkan rekomendasi sistem.
        </p>
    </div>

    {{-- Error umum --}}
    @if (session('error'))
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validasi --}}
    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-5 py-4">
            <p class="font-semibold text-red-700">
                Data belum dapat disimpan.
            </p>

            <ul class="mt-2 list-disc pl-5 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Informasi siswa --}}
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="font-semibold text-gray-800">
            Informasi Siswa
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
            <div>
                <p class="text-xs text-gray-500">
                    Nama siswa
                </p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $siswa->nama }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Kelas
                </p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $siswa->kelas->nama_kelas ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Kategori risiko
                </p>

                <p class="text-sm font-semibold text-red-600 mt-1">
                    {{ ucfirst(
                        $analisis->kategori_resiko
                    ) }}
                </p>
            </div>
        </div>
    </section>

    {{-- Rekomendasi --}}
    <section class="bg-teal-50 rounded-2xl border border-teal-100 p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-teal-600">
                    Rekomendasi sistem
                </p>

                <h2 class="font-bold text-gray-800 mt-2">
                    {{ $rekomendasi->jenis_rekomendasi }}
                </h2>

                <p class="text-sm text-gray-600 leading-relaxed mt-2">
                    {{ $rekomendasi->deskripsi }}
                </p>
            </div>

            <span class="inline-flex px-3 py-1 rounded-full border text-xs font-semibold
                {{ $rekomendasi->prioritas === 'tinggi'
                    ? 'bg-red-100 text-red-700 border-red-200'
                    : ($rekomendasi->prioritas === 'sedang'
                        ? 'bg-yellow-100 text-yellow-700 border-yellow-200'
                        : 'bg-green-100 text-green-700 border-green-200') }}"
            >
                Prioritas
                {{ ucfirst($rekomendasi->prioritas) }}
            </span>
        </div>
    </section>

    {{-- Form --}}
    <form
        method="POST"
        action="{{ route(
            'guru.tindak-lanjut.store',
            $rekomendasi->id_rekomendasi
        ) }}"
        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6"
    >
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Tanggal --}}
            <div>
                <label
                    for="tanggal"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Tanggal pelaksanaan
                </label>

                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    value="{{ old(
                        'tanggal',
                        now()->toDateString()
                    ) }}"
                    required
                    class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                >

                @error('tanggal')
                    <p class="text-xs text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Jenis tindakan --}}
            <div>
                <label
                    for="jenis_tindakan"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Jenis tindakan
                </label>

                <input
                    type="text"
                    id="jenis_tindakan"
                    name="jenis_tindakan"
                    maxlength="100"
                    value="{{ old(
                        'jenis_tindakan',
                        $rekomendasi->jenis_rekomendasi
                    ) }}"
                    required
                    class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                >

                @error('jenis_tindakan')
                    <p class="text-xs text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Catatan --}}
        <div class="mt-5">
            <label
                for="catatan"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Rencana dan catatan tindakan
            </label>

            <textarea
                id="catatan"
                name="catatan"
                rows="5"
                required
                class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                placeholder="Jelaskan tujuan, pihak yang terlibat, dan rencana pendampingan."
            >{{ old(
                'catatan',
                $rekomendasi->deskripsi
            ) }}</textarea>

            @error('catatan')
                <p class="text-xs text-red-600 mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Hasil awal --}}
        <div class="mt-5">
            <label
                for="hasil"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Hasil sementara
                <span class="font-normal text-gray-400">
                    (opsional)
                </span>
            </label>

            <textarea
                id="hasil"
                name="hasil"
                rows="4"
                class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                placeholder="Kosongkan apabila tindakan belum dilaksanakan."
            >{{ old('hasil') }}</textarea>

            @error('hasil')
                <p class="text-xs text-red-600 mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
            <a
                href="{{ route(
                    'guru.siswa.detail',
                    $siswa->id_siswa
                ) }}"
                class="inline-flex justify-center px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50"
            >
                Batal
            </a>

            <button
                type="submit"
                class="inline-flex justify-center px-5 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold hover:bg-teal-700"
            >
                Simpan Tindak Lanjut
            </button>
        </div>
    </form>
</div>
@endsection