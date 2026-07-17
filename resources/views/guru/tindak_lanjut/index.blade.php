@extends('layouts.app')

@section('title', 'Daftar Tindak Lanjut - SahabatKelas')

@section('content')
<div class="max-w-7xl mx-auto mb-10 space-y-6">

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Tindak Lanjut Siswa
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Daftar pendampingan yang dibuat berdasarkan hasil analisis dan rekomendasi sistem.
            </p>
        </div>

        <a
            href="{{ route('guru.heatmap') }}"
            class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-medium text-gray-600 hover:bg-gray-50"
        >
            Kembali ke Heatmap
        </a>
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <a
            href="{{ route('guru.tindak-lanjut.index') }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:border-teal-200"
        >
            <p class="text-sm text-gray-500">
                Seluruh Tindak Lanjut
            </p>

            <p class="text-3xl font-bold text-gray-800 mt-2">
                {{ $ringkasan['semua'] }}
            </p>
        </a>

        <a
            href="{{ route(
                'guru.tindak-lanjut.index',
                ['status' => 'proses']
            ) }}"
            class="bg-yellow-50 rounded-2xl border border-yellow-100 p-5 hover:border-yellow-300"
        >
            <p class="text-sm text-yellow-700">
                Sedang Diproses
            </p>

            <p class="text-3xl font-bold text-yellow-700 mt-2">
                {{ $ringkasan['proses'] }}
            </p>
        </a>

        <a
            href="{{ route(
                'guru.tindak-lanjut.index',
                ['status' => 'selesai']
            ) }}"
            class="bg-green-50 rounded-2xl border border-green-100 p-5 hover:border-green-300"
        >
            <p class="text-sm text-green-700">
                Selesai
            </p>

            <p class="text-3xl font-bold text-green-700 mt-2">
                {{ $ringkasan['selesai'] }}
            </p>
        </a>
    </div>

    {{-- Filter --}}
    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <form
            method="GET"
            action="{{ route('guru.tindak-lanjut.index') }}"
            class="grid grid-cols-1 md:grid-cols-4 gap-4"
        >
            <div class="md:col-span-2">
                <label
                    for="q"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Cari siswa atau tindakan
                </label>

                <input
                    type="text"
                    id="q"
                    name="q"
                    value="{{ $keyword }}"
                    placeholder="Nama siswa, NIS, atau jenis tindakan"
                    class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                >
            </div>

            <div>
                <label
                    for="status"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full rounded-xl border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                >
                    <option value="">
                        Semua status
                    </option>

                    <option
                        value="proses"
                        @selected($status === 'proses')
                    >
                        Proses
                    </option>

                    <option
                        value="selesai"
                        @selected($status === 'selesai')
                    >
                        Selesai
                    </option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button
                    type="submit"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold hover:bg-teal-700"
                >
                    Terapkan
                </button>

                <a
                    href="{{ route('guru.tindak-lanjut.index') }}"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50"
                >
                    Reset
                </a>
            </div>
        </form>
    </section>

    {{-- Daftar --}}
    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">
                Daftar Pendampingan
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Tindak lanjut aktif ditampilkan lebih dahulu.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">
                            Siswa
                        </th>

                        <th class="px-6 py-3 font-medium">
                            Risiko
                        </th>

                        <th class="px-6 py-3 font-medium">
                            Tindakan
                        </th>

                        <th class="px-6 py-3 font-medium">
                            Penanggung Jawab
                        </th>

                        <th class="px-6 py-3 font-medium">
                            Tanggal
                        </th>

                        <th class="px-6 py-3 font-medium">
                            Status
                        </th>

                        <th class="px-6 py-3 font-medium text-right">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($daftarTindakLanjut as $item)
                        @php
                            $analisis = $item->analisisResiko;
                            $siswa = $analisis?->siswa;

                            $warnaRisiko = match (
                                $analisis?->kategori_resiko
                            ) {
                                'tinggi' =>
                                    'bg-red-100 text-red-700',

                                'sedang' =>
                                    'bg-yellow-100 text-yellow-700',

                                default =>
                                    'bg-green-100 text-green-700',
                            };

                            $warnaStatus = $item->status === 'selesai'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-yellow-100 text-yellow-700';
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">
                                    {{ $siswa?->nama ?? 'Siswa tidak ditemukan' }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $siswa?->nis ?? '-' }}
                                    ·
                                    {{ $siswa?->kelas?->nama_kelas ?? '-' }}
                                </p>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $warnaRisiko }}">
                                    {{ ucfirst(
                                        $analisis?->kategori_resiko
                                            ?? 'belum'
                                    ) }}
                                </span>

                                @if ($analisis?->skor_akhir !== null)
                                    <p class="text-xs text-gray-500 mt-1">
                                        Skor
                                        {{ number_format(
                                            (float) $analisis->skor_akhir,
                                            1
                                        ) }}
                                    </p>
                                @endif
                            </td>

                            <td class="px-6 py-4 min-w-64">
                                <p class="font-medium text-gray-800">
                                    {{ $item->jenis_tindakan }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit(
                                        $item->catatan,
                                        90
                                    ) }}
                                </p>
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ $item->guru?->nama ?? 'Guru' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ \Carbon\Carbon::parse(
                                    $item->tanggal
                                )->translatedFormat('d M Y') }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $warnaStatus }}">
                                    {{ $item->status === 'selesai'
                                        ? 'Selesai'
                                        : 'Proses' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route(
                                        'guru.tindak-lanjut.show',
                                        $item->id_tindak_lanjut
                                    ) }}"
                                    class="text-sm font-semibold text-teal-600 hover:text-teal-700"
                                >
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="7"
                                class="px-6 py-12 text-center"
                            >
                                <p class="text-sm font-medium text-gray-500">
                                    Belum ada tindak lanjut.
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    Buat tindak lanjut dari rekomendasi pada halaman detail siswa.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($daftarTindakLanjut->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $daftarTindakLanjut->links() }}
            </div>
        @endif
    </section>
</div>
@endsection