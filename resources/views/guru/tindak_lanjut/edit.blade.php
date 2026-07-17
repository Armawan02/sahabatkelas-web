@extends('layouts.app')

@section('title', 'Perbarui Tindak Lanjut - SahabatKelas')

@section('content')
@php
    $warnaRisiko = match ($analisis->kategori_resiko) {
        'tinggi' =>
            'bg-red-100 text-red-700 border-red-200',

        'sedang' =>
            'bg-yellow-100 text-yellow-700 border-yellow-200',

        default =>
            'bg-green-100 text-green-700 border-green-200',
    };
@endphp

<div class="max-w-4xl mx-auto mb-10 space-y-6">

    {{-- Header --}}
    <div>
        <a
            href="{{ route(
                'guru.tindak-lanjut.show',
                $tindakLanjut->id_tindak_lanjut
            ) }}"
            class="text-sm font-medium text-teal-600 hover:text-teal-700"
        >
            ← Kembali ke detail tindak lanjut
        </a>

        <h1 class="text-2xl font-bold text-gray-800 mt-3">
            Perbarui Tindak Lanjut
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Catat hasil pelaksanaan dan perbarui status pendampingan siswa.
        </p>
    </div>

    {{-- Validasi --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">
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
    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Siswa
                </p>

                <h2 class="text-lg font-bold text-gray-800 mt-1">
                    {{ $siswa->nama }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    NIS {{ $siswa->nis ?? '-' }}
                    ·
                    {{ $siswa->kelas?->nama_kelas ?? '-' }}
                </p>
            </div>

            <span class="inline-flex px-3 py-1 rounded-full border text-xs font-semibold {{ $warnaRisiko }}">
                Risiko {{ ucfirst($analisis->kategori_resiko) }}
            </span>
        </div>
    </section>

    {{-- Form --}}
    <form
        method="POST"
        action="{{ route(
            'guru.tindak-lanjut.update',
            $tindakLanjut->id_tindak_lanjut
        ) }}"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
    >
        @csrf
        @method('PATCH')

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
                        \Carbon\Carbon::parse(
                            $tindakLanjut->tanggal
                        )->toDateString()
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

            {{-- Status --}}
            <div>
                <label
                    for="status"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Status tindak lanjut
                </label>

                <select
                    id="status"
                    name="status"
                    required
                    class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                >
                    <option
                        value="proses"
                        @selected(
                            old(
                                'status',
                                $tindakLanjut->status
                            ) === 'proses'
                        )
                    >
                        Sedang Diproses
                    </option>

                    <option
                        value="selesai"
                        @selected(
                            old(
                                'status',
                                $tindakLanjut->status
                            ) === 'selesai'
                        )
                    >
                        Selesai
                    </option>
                </select>

                <p class="text-xs text-gray-400 mt-1">
                    Pilih selesai setelah tindakan telah dilaksanakan dan dievaluasi.
                </p>

                @error('status')
                    <p class="text-xs text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Jenis tindakan --}}
        <div class="mt-5">
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
                    $tindakLanjut->jenis_tindakan
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
                maxlength="5000"
                required
                class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
            >{{ old(
                'catatan',
                $tindakLanjut->catatan
            ) }}</textarea>

            @error('catatan')
                <p class="text-xs text-red-600 mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Hasil --}}
        <div class="mt-5">
            <div class="flex items-center justify-between gap-3 mb-2">
                <label
                    for="hasil"
                    class="block text-sm font-medium text-gray-700"
                >
                    Hasil pelaksanaan
                </label>

                <span class="text-xs text-gray-400">
                    Wajib ketika status selesai
                </span>
            </div>

            <textarea
                id="hasil"
                name="hasil"
                rows="6"
                maxlength="5000"
                class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                placeholder="Jelaskan proses pelaksanaan, respons siswa, hasil sementara, dan keputusan berikutnya."
            >{{ old(
                'hasil',
                $tindakLanjut->hasil ===
                    'Belum ada hasil pelaksanaan.'
                        ? ''
                        : $tindakLanjut->hasil
            ) }}</textarea>

            <p class="text-xs text-gray-400 mt-1">
                Contoh: Konseling telah dilakukan. Siswa mulai terbuka, tetapi masih membutuhkan pemantauan mingguan.
            </p>

            @error('hasil')
                <p class="text-xs text-red-600 mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Tombol --}}
        <div class="mt-7 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
            <a
                href="{{ route(
                    'guru.tindak-lanjut.show',
                    $tindakLanjut->id_tindak_lanjut
                ) }}"
                class="inline-flex justify-center px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50"
            >
                Batal
            </a>

            <button
                type="submit"
                class="inline-flex justify-center px-5 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold hover:bg-teal-700"
            >
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection