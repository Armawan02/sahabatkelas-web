@extends('layouts.app')

@section('title', 'Monitoring Intervensi - SahabatKelas')

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
            Tambah Monitoring Intervensi
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Catat perkembangan siswa setelah tindak lanjut dilakukan.
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

            <div class="text-right">
                <span class="inline-flex px-3 py-1 rounded-full border text-xs font-semibold {{ $warnaRisiko }}">
                    Risiko {{ ucfirst($analisis->kategori_resiko) }}
                </span>

                <p class="text-sm font-bold text-gray-800 mt-2">
                    Skor
                    {{ number_format(
                        (float) $analisis->skor_akhir,
                        1
                    ) }}
                </p>
            </div>
        </div>

        <div class="mt-5 pt-5 border-t border-gray-100">
            <p class="text-xs text-gray-500">
                Tindak lanjut
            </p>

            <p class="text-sm font-semibold text-gray-800 mt-1">
                {{ $tindakLanjut->jenis_tindakan }}
            </p>
        </div>
    </section>

    {{-- Form --}}
    <form
        method="POST"
        action="{{ route(
            'guru.monitoring.store',
            $tindakLanjut->id_tindak_lanjut
        ) }}"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
    >
        @csrf

        {{-- Tanggal --}}
        <div>
            <label
                for="tanggal_monitoring"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Tanggal monitoring
            </label>

            <input
                type="date"
                id="tanggal_monitoring"
                name="tanggal_monitoring"
                value="{{ old(
                    'tanggal_monitoring',
                    now()->toDateString()
                ) }}"
                required
                class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
            >
        </div>

        {{-- Indikator kondisi --}}
        <div class="mt-6">
            <h2 class="font-semibold text-gray-800">
                Kondisi Siswa
            </h2>

            <p class="text-xs text-gray-500 mt-1">
                Nilai 1 menunjukkan kondisi kurang baik dan 4 menunjukkan kondisi sangat baik.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-4">

                @foreach ([
                    'perasaan_aman' =>
                        'Perasaan Aman',

                    'interaksi_sosial' =>
                        'Interaksi Sosial',

                    'keterlibatan_belajar' =>
                        'Keterlibatan Belajar',
                ] as $nama => $label)
                    <div>
                        <label
                            for="{{ $nama }}"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            {{ $label }}
                        </label>

                        <select
                            id="{{ $nama }}"
                            name="{{ $nama }}"
                            class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                        >
                            <option value="">
                                Belum dinilai
                            </option>

                            <option
                                value="1"
                                @selected(old($nama) == 1)
                            >
                                1 — Sangat kurang
                            </option>

                            <option
                                value="2"
                                @selected(old($nama) == 2)
                            >
                                2 — Kurang
                            </option>

                            <option
                                value="3"
                                @selected(old($nama) == 3)
                            >
                                3 — Baik
                            </option>

                            <option
                                value="4"
                                @selected(old($nama) == 4)
                            >
                                4 — Sangat baik
                            </option>
                        </select>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Hasil evaluasi --}}
        <div class="mt-6">
            <label
                for="hasil_evaluasi"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Hasil evaluasi
            </label>

            <select
                id="hasil_evaluasi"
                name="hasil_evaluasi"
                required
                class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
            >
                <option value="">
                    Pilih hasil evaluasi
                </option>

                <option
                    value="membaik"
                    @selected(
                        old('hasil_evaluasi')
                        === 'membaik'
                    )
                >
                    Kondisi Membaik
                </option>

                <option
                    value="tetap"
                    @selected(
                        old('hasil_evaluasi')
                        === 'tetap'
                    )
                >
                    Kondisi Tetap
                </option>

                <option
                    value="memburuk"
                    @selected(
                        old('hasil_evaluasi')
                        === 'memburuk'
                    )
                >
                    Kondisi Memburuk
                </option>
            </select>
        </div>

        {{-- Keterangan siswa --}}
        <div class="mt-6">
            <label
                for="catatan_siswa"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Keterangan siswa
                <span class="font-normal text-gray-400">
                    (opsional)
                </span>
            </label>

            <textarea
                id="catatan_siswa"
                name="catatan_siswa"
                rows="4"
                maxlength="5000"
                class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                placeholder="Tuliskan kondisi atau keterangan yang disampaikan siswa."
            >{{ old('catatan_siswa') }}</textarea>
        </div>

        {{-- Catatan guru --}}
        <div class="mt-6">
            <label
                for="catatan_guru"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Catatan hasil monitoring
            </label>

            <textarea
                id="catatan_guru"
                name="catatan_guru"
                rows="5"
                maxlength="5000"
                required
                class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                placeholder="Jelaskan perubahan kondisi, respons siswa, dan hasil pengamatan guru."
            >{{ old('catatan_guru') }}</textarea>
        </div>

        {{-- Tindakan berikutnya --}}
        <div class="mt-6">
            <label
                for="tindakan_berikutnya"
                class="block text-sm font-medium text-gray-700 mb-2"
            >
                Keputusan berikutnya
            </label>

            <select
                id="tindakan_berikutnya"
                name="tindakan_berikutnya"
                required
                class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
            >
                <option value="">
                    Pilih keputusan
                </option>

                <option
                    value="lanjut_monitoring"
                    @selected(
                        old('tindakan_berikutnya')
                        === 'lanjut_monitoring'
                    )
                >
                    Lanjutkan Monitoring
                </option>

                <option
                    value="tindakan_tambahan"
                    @selected(
                        old('tindakan_berikutnya')
                        === 'tindakan_tambahan'
                    )
                >
                    Buat Tindakan Tambahan
                </option>

                <option
                    value="rujuk"
                    @selected(
                        old('tindakan_berikutnya')
                        === 'rujuk'
                    )
                >
                    Rujuk ke Pihak Lain
                </option>

                <option
                    value="selesai"
                    @selected(
                        old('tindakan_berikutnya')
                        === 'selesai'
                    )
                >
                    Selesaikan Tindak Lanjut
                </option>
            </select>

            <p class="text-xs text-gray-400 mt-1">
                Memilih “Selesaikan Tindak Lanjut” akan mengubah status menjadi selesai.
            </p>
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
                Simpan Monitoring
            </button>
        </div>
    </form>
</div>
@endsection