<?php

namespace App\Http\Controllers;

use App\Models\AnalisisResiko;
use App\Models\CheckIn;
use App\Models\Kelas;
use App\Models\MonitoringIntervensi;
use App\Models\Observasi;
use App\Models\SafeReport;
use App\Models\Siswa;
use App\Services\RiskCalculationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Rekomendasi;
use App\Models\TindakLanjut;
use Illuminate\Support\Facades\DB;




class GuruController extends Controller
{
    /**
     * Menampilkan dashboard guru.
     */
    /**
     * Menampilkan dashboard utama guru.
     */
    public function index(): View
    {
        $guru = Auth::user()?->guru;

        abort_if(
            $guru === null,
            403,
            'Data guru tidak ditemukan.'
        );

        /*
     * Rentang minggu berjalan:
     * Senin sampai Minggu.
     */
        $awalMinggu = now()
            ->startOfWeek()
            ->toDateString();

        $akhirMinggu = now()
            ->endOfWeek()
            ->toDateString();

        /*
     * Mengambil siswa yang analisis TERBARUNYA
     * berada pada kategori risiko tinggi.
     *
     * Relasi analisisTerbaru menggunakan latestOfMany,
     * sehingga riwayat lama tidak ikut dihitung.
     */
        $querySiswaRisikoTinggi = Siswa::query()
            ->whereHas(
                'analisisTerbaru',
                function ($query) {
                    $query->where(
                        'kategori_resiko',
                        'tinggi'
                    );
                }
            );

        $totalRisikoTinggi = (clone $querySiswaRisikoTinggi)
            ->count();

        $siswaRisikoTinggi = (clone $querySiswaRisikoTinggi)
            ->with([
                'kelas',
                'analisisTerbaru',
            ])
            ->get()
            ->sortByDesc(
                function ($siswa) {
                    return (float) (
                        $siswa->analisisTerbaru?->skor_akhir
                        ?? 0
                    );
                }
            )
            ->take(5)
            ->values();

        /*
     * Safe Report yang belum ditinjau.
     */
        $totalLaporanBaru = SafeReport::query()
            ->where('status', 'menunggu')
            ->count();

        /*
     * Lima laporan terbaru.
     */
        $laporanTerbaru = SafeReport::query()
            ->with('siswa')
            ->latest('created_at')
            ->limit(5)
            ->get();

        /*
     * Menghitung siswa unik yang sudah
     * melakukan check-in minggu ini.
     */
        $totalCheckInMingguIni = CheckIn::query()
            ->whereBetween(
                'tanggal',
                [
                    $awalMinggu,
                    $akhirMinggu,
                ]
            )
            ->distinct()
            ->count('id_siswa');

        $totalSiswa = Siswa::query()
            ->count();

        /*
     * Tindak lanjut yang masih aktif.
     */
        $totalTindakLanjutAktif = TindakLanjut::query()
            ->where('status', 'proses')
            ->count();

        return view(
            'guru.dashboard',
            compact(
                'guru',
                'totalRisikoTinggi',
                'totalLaporanBaru',
                'totalCheckInMingguIni',
                'totalSiswa',
                'totalTindakLanjutAktif',
                'siswaRisikoTinggi',
                'laporanTerbaru'
            )
        );
    }

    /**
     * Menampilkan heatmap risiko per kelas.
     */
    public function heatmap(): View
    {
        $dataKelas = Kelas::query()
            ->with([
                'siswa' => function ($query) {
                    $query
                        ->where('status', 'aktif')
                        ->with('analisisTerbaru')
                        ->orderBy('nama');
                },
            ])
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get()
            ->map(function ($kelas) {
                $jumlahSiswa = $kelas->siswa->count();

                $risikoRendah = $kelas->siswa
                    ->filter(function ($siswa) {
                        return $siswa->analisisTerbaru?->kategori_resiko
                            === 'rendah';
                    })
                    ->count();

                $risikoSedang = $kelas->siswa
                    ->filter(function ($siswa) {
                        return $siswa->analisisTerbaru?->kategori_resiko
                            === 'sedang';
                    })
                    ->count();

                $risikoTinggi = $kelas->siswa
                    ->filter(function ($siswa) {
                        return $siswa->analisisTerbaru?->kategori_resiko
                            === 'tinggi';
                    })
                    ->count();

                $belumDianalisis = $kelas->siswa
                    ->filter(function ($siswa) {
                        return $siswa->analisisTerbaru === null;
                    })
                    ->count();

                $jumlahDianalisis =
                    $risikoRendah
                    + $risikoSedang
                    + $risikoTinggi;

                /*
             * Indeks hanya dihitung dari siswa
             * yang sudah memiliki hasil analisis.
             */
                $indeksRisiko = $jumlahDianalisis > 0
                    ? round(
                        (
                            ($risikoRendah * 1)
                            + ($risikoSedang * 2)
                            + ($risikoTinggi * 3)
                        ) / ($jumlahDianalisis * 3) * 100,
                        1
                    )
                    : null;

                $cakupanAnalisis = $jumlahSiswa > 0
                    ? round(
                        $jumlahDianalisis / $jumlahSiswa * 100,
                        1
                    )
                    : 0;

                /*
             * Status kelas mengikuti tingkat risiko
             * tertinggi yang ditemukan.
             */
                if ($risikoTinggi > 0) {
                    $kategoriKelas = 'tinggi';
                } elseif ($risikoSedang > 0) {
                    $kategoriKelas = 'sedang';
                } elseif ($risikoRendah > 0) {
                    $kategoriKelas = 'rendah';
                } else {
                    $kategoriKelas = 'belum';
                }

                $kelas->jumlah_siswa = $jumlahSiswa;
                $kelas->jumlah_dianalisis = $jumlahDianalisis;
                $kelas->risiko_rendah = $risikoRendah;
                $kelas->risiko_sedang = $risikoSedang;
                $kelas->risiko_tinggi = $risikoTinggi;
                $kelas->belum_dianalisis = $belumDianalisis;
                $kelas->indeks_risiko = $indeksRisiko;
                $kelas->cakupan_analisis = $cakupanAnalisis;
                $kelas->kategori_kelas = $kategoriKelas;

                return $kelas;
            });

        $jumlahSiswa = $dataKelas->sum('jumlah_siswa');
        $jumlahDianalisis = $dataKelas->sum('jumlah_dianalisis');

        $ringkasan = [
            'jumlah_kelas' => $dataKelas->count(),
            'jumlah_siswa' => $jumlahSiswa,
            'jumlah_dianalisis' => $jumlahDianalisis,
            'risiko_rendah' => $dataKelas->sum('risiko_rendah'),
            'risiko_sedang' => $dataKelas->sum('risiko_sedang'),
            'risiko_tinggi' => $dataKelas->sum('risiko_tinggi'),
            'belum_dianalisis' => $dataKelas->sum('belum_dianalisis'),
            'cakupan_analisis' => $jumlahSiswa > 0
                ? round($jumlahDianalisis / $jumlahSiswa * 100, 1)
                : 0,
        ];

        return view(
            'guru.heatmap',
            compact('dataKelas', 'ringkasan')
        );
    }

    /**
     * Menampilkan detail risiko seorang siswa.
     */
    public function detailSiswa(int $id_siswa): View
    {
        $siswa = Siswa::query()
            ->withCount([
                'checkIns',
                'safeReports',
                'observasi',
            ])
            ->with([
                'kelas',

                'checkIns' => function ($query) {
                    $query
                        ->latest('tanggal')
                        ->limit(5);
                },

                'safeReports' => function ($query) {
                    $query
                        ->latest('created_at')
                        ->limit(5);
                },

                'observasi' => function ($query) {
                    $query
                        ->with('guru')
                        ->latest('tanggal')
                        ->limit(5);
                },

                'analisisTerbaru' => function ($query) {
                    $query->with([
                        'hasilNlp' => function ($query) {
                            $query->latest('updated_at');
                        },

                        'rekomendasi',

                        'tindakLanjut.guru',
                    ]);
                },

                /*
             * Tetap dibutuhkan oleh tabel
             * Riwayat Analisis Risiko.
             */
                'analisisResiko' => function ($query) {
                    $query
                        ->latest('tanggal_analisis')
                        ->limit(10);
                },
            ])
            ->findOrFail($id_siswa);

        $analisisTerbaru = $siswa->analisisTerbaru;

        return view(
            'guru.detail_siswa',
            compact(
                'siswa',
                'analisisTerbaru'
            )
        );
    }

    /**
     * Menampilkan form observasi guru.
     */
    public function createObservasi(int $id_siswa): View
    {
        $siswa = Siswa::query()
            ->with('kelas')
            ->findOrFail($id_siswa);

        return view(
            'guru.form_observasi',
            compact('siswa')
        );
    }

    /**
     * Menyimpan observasi dan menghitung ulang
     * skor risiko siswa.
     */
    public function storeObservasi(
        Request $request,
        int $id_siswa,
        RiskCalculationService $riskService
    ): RedirectResponse {
        $validated = $request->validate([
            'perubahan_perilaku' => [
                'required',
                'in:0,1,2,3,4',
            ],
            'interaksi' => [
                'required',
                'in:0,1,2,3,4',
            ],
            'kenyamanan' => [
                'required',
                'in:0,1,2,3,4',
            ],
            'isolasi' => [
                'required',
                'in:0,1,2,3,4',
            ],
            'tekanan' => [
                'required',
                'in:0,1,2,3,4',
            ],
            'agresif' => [
                'required',
                'in:0,1,2,3,4',
            ],
            'perlu_tindak_lanjut' => [
                'required',
                'in:ya,tidak',
            ],
            'catatan' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $guru = Auth::user()?->guru;

        abort_if(
            $guru === null,
            403,
            'Data guru tidak ditemukan.'
        );

        /*
         * Memastikan siswa benar-benar tersedia.
         */
        Siswa::query()->findOrFail($id_siswa);

        Observasi::create([
            'id_siswa' => $id_siswa,
            'id_guru' => $guru->id_guru,
            'tanggal' => now()->toDateString(),

            'perubahan_perilaku' =>
            $validated['perubahan_perilaku'],

            'interaksi' =>
            $validated['interaksi'],

            'kenyamanan' =>
            $validated['kenyamanan'],

            'isolasi' =>
            $validated['isolasi'],

            'tekanan' =>
            $validated['tekanan'],

            'agresif' =>
            $validated['agresif'],

            'perlu_tindak_lanjut' =>
            $validated['perlu_tindak_lanjut'],

            'catatan' =>
            $validated['catatan'] ?? null,
        ]);

        /*
         * Menghitung kembali risiko setelah observasi masuk.
         */
        $riskService->recalculateRisk($id_siswa);

        return redirect()
            ->route(
                'guru.siswa.detail',
                $id_siswa
            )
            ->with(
                'success',
                'Data observasi berhasil ditambahkan dan skor risiko siswa telah diperbarui.'
            );
    }

    /**
     * Menampilkan form tindak lanjut
     * berdasarkan rekomendasi sistem.
     */
    public function createTindakLanjut(
        int $id_rekomendasi
    ): View {
        $rekomendasi = Rekomendasi::query()
            ->with([
                'analisisResiko.siswa.kelas',
            ])
            ->findOrFail($id_rekomendasi);

        return view(
            'guru.tindak_lanjut.create',
            compact('rekomendasi')
        );
    }


    /**
     * Menyimpan tindak lanjut guru.
     */
    public function storeTindakLanjut(
        Request $request,
        int $id_rekomendasi
    ) {
        $validated = $request->validate([
            'tanggal' => [
                'required',
                'date',
            ],

            'jenis_tindakan' => [
                'required',
                'string',
                'max:100',
            ],

            'catatan' => [
                'required',
                'string',
                'max:5000',
            ],

            'hasil' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $guru = Auth::user()?->guru;

        abort_if(
            $guru === null,
            403,
            'Data guru tidak ditemukan.'
        );

        $rekomendasi = Rekomendasi::query()
            ->with('analisisResiko')
            ->findOrFail($id_rekomendasi);

        /*
     * Mencegah rekomendasi yang sama
     * dibuat berulang kali.
     */
        if ($rekomendasi->status === 'diterapkan') {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Rekomendasi ini sudah diterapkan.'
                );
        }

        DB::transaction(function () use (
            $validated,
            $guru,
            $rekomendasi
        ) {
            TindakLanjut::create([
                'id_analisis' =>
                $rekomendasi->id_analisis,

                'id_guru' =>
                $guru->id_guru,

                'tanggal' =>
                $validated['tanggal'],

                'jenis_tindakan' =>
                $validated['jenis_tindakan'],

                'catatan' =>
                $validated['catatan'],

                /*
             * Kolom hasil pada database tidak nullable.
             * Karena tindak lanjut baru dibuat,
             * gunakan keterangan awal.
             */
                'hasil' =>
                $validated['hasil']
                    ?: 'Belum ada hasil pelaksanaan.',

                'status' => 'proses',
            ]);

            /*
         * Menandai rekomendasi telah dipilih guru.
         */
            $rekomendasi->update([
                'status' => 'diterapkan',
            ]);
        });

        $idSiswa = $rekomendasi
            ->analisisResiko
            ->id_siswa;

        return redirect()
            ->route(
                'guru.siswa.detail',
                $idSiswa
            )
            ->with(
                'success',
                'Tindak lanjut berhasil dibuat.'
            );
    }

    /**
     * Menampilkan daftar tindak lanjut guru.
     */
    public function indexTindakLanjut(
        Request $request
    ): View {
        $status = (string) $request->query(
            'status',
            ''
        );

        $keyword = trim(
            (string) $request->query(
                'q',
                ''
            )
        );

        $query = TindakLanjut::query()
            ->with([
                'guru',
                'analisisResiko.siswa.kelas',
            ]);

        /*
     * Filter status.
     */
        if (
            in_array(
                $status,
                ['proses', 'selesai'],
                true
            )
        ) {
            $query->where(
                'status',
                $status
            );
        }

        /*
     * Pencarian berdasarkan nama siswa,
     * NIS, atau jenis tindakan.
     */
        if ($keyword !== '') {
            $query->where(
                function ($subQuery) use ($keyword) {
                    $subQuery
                        ->where(
                            'jenis_tindakan',
                            'like',
                            '%' . $keyword . '%'
                        )
                        ->orWhereHas(
                            'analisisResiko.siswa',
                            function ($siswaQuery) use ($keyword) {
                                $siswaQuery
                                    ->where(
                                        'nama',
                                        'like',
                                        '%' . $keyword . '%'
                                    )
                                    ->orWhere(
                                        'nis',
                                        'like',
                                        '%' . $keyword . '%'
                                    );
                            }
                        );
                }
            );
        }

        /*
     * Tindak lanjut berstatus proses
     * ditampilkan lebih dahulu.
     */
        $daftarTindakLanjut = $query
            ->orderByRaw(
                "CASE
                WHEN status = 'proses' THEN 0
                ELSE 1
            END"
            )
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $ringkasan = [
            'semua' => TindakLanjut::query()
                ->count(),

            'proses' => TindakLanjut::query()
                ->where('status', 'proses')
                ->count(),

            'selesai' => TindakLanjut::query()
                ->where('status', 'selesai')
                ->count(),
        ];

        return view(
            'guru.tindak_lanjut.index',
            compact(
                'daftarTindakLanjut',
                'ringkasan',
                'status',
                'keyword'
            )
        );
    }

    /**
     * Menampilkan detail satu tindak lanjut.
     */
    public function showTindakLanjut(
        int $id_tindak_lanjut
    ): View {
        $tindakLanjut = TindakLanjut::query()
            ->with([
                'guru',

                'analisisResiko.siswa.kelas',

                'analisisResiko.rekomendasi',

                'monitoringIntervensi.guru',
            ])
            ->findOrFail($id_tindak_lanjut);

        $analisis = $tindakLanjut
            ->analisisResiko;

        abort_if(
            $analisis === null,
            404,
            'Analisis risiko tidak ditemukan.'
        );

        $siswa = $analisis->siswa;

        /*
     * Karena tabel tindak_lanjut belum menyimpan
     * id_rekomendasi, rekomendasi terkait diambil
     * dari analisis yang sama.
     */
        $rekomendasiTerkait = $analisis
            ->rekomendasi
            ->where('status', 'diterapkan')
            ->values();

        $daftarMonitoring = $tindakLanjut
            ->monitoringIntervensi;

        return view(
            'guru.tindak_lanjut.show',
            compact(
                'tindakLanjut',
                'analisis',
                'siswa',
                'rekomendasiTerkait',
                'daftarMonitoring'
            )
        );
    }

    /**
     * Menampilkan form pembaruan tindak lanjut.
     */
    public function editTindakLanjut(
        int $id_tindak_lanjut
    ): View {
        $tindakLanjut = TindakLanjut::query()
            ->with([
                'guru',
                'analisisResiko.siswa.kelas',
            ])
            ->findOrFail($id_tindak_lanjut);

        $analisis = $tindakLanjut->analisisResiko;

        abort_if(
            $analisis === null,
            404,
            'Analisis risiko tidak ditemukan.'
        );

        $siswa = $analisis->siswa;

        return view(
            'guru.tindak_lanjut.edit',
            compact(
                'tindakLanjut',
                'analisis',
                'siswa'
            )
        );
    }

    /**
     * Memperbarui hasil dan status tindak lanjut.
     */
    public function updateTindakLanjut(
        Request $request,
        int $id_tindak_lanjut
    ): RedirectResponse {
        $tindakLanjut = TindakLanjut::query()
            ->with('analisisResiko')
            ->findOrFail($id_tindak_lanjut);

        $validated = $request->validate([
            'tanggal' => [
                'required',
                'date',
            ],

            'jenis_tindakan' => [
                'required',
                'string',
                'max:100',
            ],

            'catatan' => [
                'required',
                'string',
                'max:5000',
            ],

            'hasil' => [
                'nullable',
                'string',
                'max:5000',
                'required_if:status,selesai',
            ],

            'status' => [
                'required',
                'in:proses,selesai',
            ],
        ], [
            'hasil.required_if' =>
            'Hasil pelaksanaan wajib diisi apabila tindak lanjut diselesaikan.',
        ]);

        /*
     * Saat status masih proses dan hasil dikosongkan,
     * gunakan keterangan standar.
     */
        $hasil = trim(
            (string) ($validated['hasil'] ?? '')
        );

        if ($hasil === '') {
            $hasil = 'Belum ada hasil pelaksanaan.';
        }

        $tindakLanjut->update([
            'tanggal' =>
            $validated['tanggal'],

            'jenis_tindakan' =>
            $validated['jenis_tindakan'],

            'catatan' =>
            $validated['catatan'],

            'hasil' =>
            $hasil,

            'status' =>
            $validated['status'],
        ]);

        $pesan = $validated['status'] === 'selesai'
            ? 'Tindak lanjut berhasil diselesaikan.'
            : 'Tindak lanjut berhasil diperbarui.';

        return redirect()
            ->route(
                'guru.tindak-lanjut.show',
                $tindakLanjut->id_tindak_lanjut
            )
            ->with(
                'success',
                $pesan
            );
    }

    /**
     * Menampilkan form monitoring intervensi.
     */
    public function createMonitoring(
        int $id_tindak_lanjut
    ): View|RedirectResponse {
        $tindakLanjut = TindakLanjut::query()
            ->with([
                'guru',
                'analisisResiko.siswa.kelas',
            ])
            ->findOrFail($id_tindak_lanjut);

        if ($tindakLanjut->status === 'selesai') {
            return redirect()
                ->route(
                    'guru.tindak-lanjut.show',
                    $tindakLanjut->id_tindak_lanjut
                )
                ->with(
                    'error',
                    'Tindak lanjut sudah selesai dan tidak dapat ditambahkan monitoring baru.'
                );
        }

        $analisis = $tindakLanjut
            ->analisisResiko;

        abort_if(
            $analisis === null,
            404,
            'Analisis risiko tidak ditemukan.'
        );

        $siswa = $analisis->siswa;

        return view(
            'guru.monitoring.create',
            compact(
                'tindakLanjut',
                'analisis',
                'siswa'
            )
        );
    }

    /**
     * Menyimpan hasil monitoring intervensi.
     */
    public function storeMonitoring(
        Request $request,
        int $id_tindak_lanjut
    ): RedirectResponse {
        $tindakLanjut = TindakLanjut::query()
            ->with([
                'analisisResiko',
            ])
            ->findOrFail($id_tindak_lanjut);

        if ($tindakLanjut->status === 'selesai') {
            return redirect()
                ->route(
                    'guru.tindak-lanjut.show',
                    $tindakLanjut->id_tindak_lanjut
                )
                ->with(
                    'error',
                    'Tindak lanjut sudah selesai.'
                );
        }

        $validated = $request->validate([
            'tanggal_monitoring' => [
                'required',
                'date',
            ],

            'perasaan_aman' => [
                'nullable',
                'integer',
                'between:1,4',
            ],

            'interaksi_sosial' => [
                'nullable',
                'integer',
                'between:1,4',
            ],

            'keterlibatan_belajar' => [
                'nullable',
                'integer',
                'between:1,4',
            ],

            'hasil_evaluasi' => [
                'required',
                'in:membaik,tetap,memburuk',
            ],

            'catatan_siswa' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'catatan_guru' => [
                'required',
                'string',
                'max:5000',
            ],

            'tindakan_berikutnya' => [
                'required',
                'in:lanjut_monitoring,tindakan_tambahan,rujuk,selesai',
            ],
        ]);

        $guru = Auth::user()?->guru;

        abort_if(
            $guru === null,
            403,
            'Data guru tidak ditemukan.'
        );

        $analisis = $tindakLanjut
            ->analisisResiko;

        abort_if(
            $analisis === null,
            404,
            'Analisis risiko tidak ditemukan.'
        );

        DB::transaction(function () use (
            $validated,
            $guru,
            $analisis,
            $tindakLanjut
        ) {
            MonitoringIntervensi::query()
                ->create([
                    'id_tindak_lanjut' =>
                    $tindakLanjut->id_tindak_lanjut,

                    'id_guru' =>
                    $guru->id_guru,

                    'tanggal_monitoring' =>
                    $validated['tanggal_monitoring'],

                    'perasaan_aman' =>
                    $validated['perasaan_aman']
                        ?? null,

                    'interaksi_sosial' =>
                    $validated['interaksi_sosial']
                        ?? null,

                    'keterlibatan_belajar' =>
                    $validated['keterlibatan_belajar']
                        ?? null,

                    'hasil_evaluasi' =>
                    $validated['hasil_evaluasi'],

                    'catatan_siswa' =>
                    $validated['catatan_siswa']
                        ?? null,

                    'catatan_guru' =>
                    $validated['catatan_guru'],

                    'tindakan_berikutnya' =>
                    $validated['tindakan_berikutnya'],

                    /*
                 * Snapshot skor saat monitoring dibuat.
                 */
                    'skor_risiko' =>
                    $analisis->skor_akhir,

                    'kategori_risiko' =>
                    $analisis->kategori_resiko,
                ]);

            /*
         * Guru secara eksplisit memilih
         * menyelesaikan tindak lanjut.
         */
            if (
                $validated['tindakan_berikutnya']
                === 'selesai'
            ) {
                $hasilSekarang = trim(
                    (string) $tindakLanjut->hasil
                );

                if (
                    $hasilSekarang === ''
                    || $hasilSekarang ===
                    'Belum ada hasil pelaksanaan.'
                ) {
                    $hasilSekarang =
                        $validated['catatan_guru'];
                }

                $tindakLanjut->update([
                    'hasil' => $hasilSekarang,
                    'status' => 'selesai',
                ]);
            } else {
                $tindakLanjut->update([
                    'status' => 'proses',
                ]);
            }
        });

        $pesan = (
            $validated['tindakan_berikutnya']
            === 'selesai'
        )
            ? 'Monitoring berhasil disimpan dan tindak lanjut diselesaikan.'
            : 'Monitoring intervensi berhasil disimpan.';

        return redirect()
            ->route(
                'guru.tindak-lanjut.show',
                $tindakLanjut->id_tindak_lanjut
            )
            ->with(
                'success',
                $pesan
            );
    }
}
