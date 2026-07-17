<?php

namespace App\Services;

use App\Models\AnalisisResiko;
use App\Models\Rekomendasi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    /**
     * Daftar rekomendasi yang dibuat otomatis oleh sistem.
     *
     * Digunakan agar sistem hanya membersihkan rekomendasi
     * otomatis yang sudah tidak sesuai, tanpa menghapus
     * rekomendasi manual atau rekomendasi dari seeder.
     */
    private const AUTOMATIC_RECOMMENDATION_TYPES = [
        'Konseling Individual Segera',
        'Pendampingan dan Pemantauan Berkala',
        'Pemantauan Rutin',
        'Respons Permintaan Bantuan Mendesak',
        'Penyusunan Rencana Keamanan Siswa',
        'Penelusuran Kejadian Berulang',
        'Penanganan Perundungan Fisik',
        'Penanganan Perundungan Verbal',
        'Pemulihan Relasi Sosial',
        'Keamanan Digital dan Dokumentasi Bukti',
        'Koordinasi Guru BK dan Wali Kelas',
        'Pemantauan Kondisi Emosional',
    ];

    /**
     * Membuat atau memperbarui rekomendasi berdasarkan
     * hasil analisis risiko dan indikator sumber data.
     */
    public function generate(
        AnalisisResiko $analisis
    ): Collection {
        /*
         * Memastikan sumber data analisis sudah dimuat.
         */
        $analisis->loadMissing([
            'checkIn',
            'safeReport',
            'observasi',
        ]);

        $aturan = $this->buildRules($analisis);

        return DB::transaction(
            function () use ($analisis, $aturan) {
                $jenisAktif = array_column(
                    $aturan,
                    'jenis_rekomendasi'
                );

                /*
                 * Menghapus rekomendasi otomatis berstatus menunggu
                 * yang tidak lagi sesuai dengan kondisi terbaru.
                 *
                 * Rekomendasi yang sudah diterapkan atau diabaikan
                 * tidak dihapus agar riwayat tetap tersimpan.
                 */
                Rekomendasi::query()
                    ->where(
                        'id_analisis',
                        $analisis->id_analisis
                    )
                    ->where('status', 'menunggu')
                    ->whereIn(
                        'jenis_rekomendasi',
                        self::AUTOMATIC_RECOMMENDATION_TYPES
                    )
                    ->whereNotIn(
                        'jenis_rekomendasi',
                        $jenisAktif
                    )
                    ->delete();

                /*
                 * Membuat rekomendasi baru atau memperbarui
                 * rekomendasi yang sudah pernah dibuat.
                 */
                foreach ($aturan as $item) {
                    $rekomendasi = Rekomendasi::query()
                        ->firstOrNew([
                            'id_analisis' =>
                            $analisis->id_analisis,

                            'jenis_rekomendasi' =>
                            $item['jenis_rekomendasi'],
                        ]);

                    $rekomendasi->deskripsi =
                        $item['deskripsi'];

                    $rekomendasi->prioritas =
                        $item['prioritas'];

                    /*
                     * Status hanya ditetapkan saat data baru dibuat.
                     * Rekomendasi yang sudah diterapkan tidak
                     * dikembalikan menjadi menunggu.
                     */
                    if (!$rekomendasi->exists) {
                        $rekomendasi->status = 'menunggu';
                    }

                    $rekomendasi->save();
                }

                return Rekomendasi::query()
                    ->where(
                        'id_analisis',
                        $analisis->id_analisis
                    )
                    ->orderByRaw(
                        "
                        CASE prioritas
                            WHEN 'tinggi' THEN 1
                            WHEN 'sedang' THEN 2
                            ELSE 3
                        END
                        "
                    )
                    ->orderBy('id_rekomendasi')
                    ->get();
            }
        );
    }

    /**
     * Menentukan aturan rekomendasi yang aktif.
     */
    private function buildRules(
        AnalisisResiko $analisis
    ): array {
        $aturan = [];

        $checkIn = $analisis->checkIn;
        $report = $analisis->safeReport;
        $observasi = $analisis->observasi;

        /*
         * Rekomendasi utama berdasarkan kategori risiko.
         */
        match ($analisis->kategori_resiko) {
            'tinggi' => $this->addRule(
                $aturan,
                'Konseling Individual Segera',
                'Jadwalkan pendampingan individual bersama guru BK secepatnya sesuai prosedur sekolah. Lakukan percakapan secara privat, aman, dan tidak menghakimi untuk mengidentifikasi kebutuhan siswa.',
                'tinggi'
            ),

            'sedang' => $this->addRule(
                $aturan,
                'Pendampingan dan Pemantauan Berkala',
                'Lakukan pendekatan personal dan pemantauan berkala terhadap kondisi emosional, interaksi sosial, serta kenyamanan belajar siswa.',
                'sedang'
            ),

            default => $this->addRule(
                $aturan,
                'Pemantauan Rutin',
                'Lanjutkan check-in dan pemantauan rutin untuk memastikan kondisi siswa tetap aman dan stabil.',
                'rendah'
            ),
        };

        /*
         * Siswa meminta bantuan mendesak melalui Check-in.
         */
        if (
            $checkIn
            && $checkIn->ingin_dibantu === 'ya_mendesak'
        ) {
            $this->addRule(
                $aturan,
                'Respons Permintaan Bantuan Mendesak',
                'Hubungi siswa secara privat dan segera pastikan kondisi keamanannya. Eskalasikan kepada guru BK atau pihak sekolah yang berwenang sesuai prosedur perlindungan siswa.',
                'tinggi'
            );
        }

        /*
         * Safe Report menunjukkan siswa merasa tidak aman
         * atau laporan memiliki prioritas tinggi.
         */
        if (
            $report
            && (
                $report->rasa_tidak_aman === 'ya'
                || $report->prioritas === 'tinggi'
            )
        ) {
            $this->addRule(
                $aturan,
                'Penyusunan Rencana Keamanan Siswa',
                'Susun langkah perlindungan bersama siswa, seperti menentukan guru yang dapat dihubungi, area aman di sekolah, pendamping saat diperlukan, dan mekanisme pelaporan lanjutan.',
                'tinggi'
            );
        }

        /*
         * Kejadian dilaporkan berlangsung berulang.
         */
        if (
            $report
            && $report->berulang === 'ya'
        ) {
            $this->addRule(
                $aturan,
                'Penelusuran Kejadian Berulang',
                'Lakukan penelusuran pola kejadian, waktu, lokasi, pihak yang terlibat, serta saksi. Dokumentasikan hasilnya untuk menentukan intervensi yang tepat.',
                'tinggi'
            );
        }

        /*
         * Rekomendasi berdasarkan jenis perundungan.
         */
        if ($report) {
            match ($report->jenis) {
                'fisik' => $this->addRule(
                    $aturan,
                    'Penanganan Perundungan Fisik',
                    'Pastikan kondisi fisik dan keamanan siswa. Dokumentasikan kejadian dan koordinasikan pemeriksaan atau rujukan kepada pihak yang kompeten apabila diperlukan.',
                    'tinggi'
                ),

                'verbal' => $this->addRule(
                    $aturan,
                    'Penanganan Perundungan Verbal',
                    'Berikan dukungan kepada siswa, identifikasi pola ejekan atau penghinaan, dan lakukan intervensi edukatif terhadap pihak yang terlibat tanpa mempertemukan korban secara langsung sebelum asesmen keamanan.',
                    'sedang'
                ),

                'sosial' => $this->addRule(
                    $aturan,
                    'Pemulihan Relasi Sosial',
                    'Pantau indikasi pengucilan dan dukung keterlibatan siswa dalam aktivitas sosial yang aman. Pertimbangkan dukungan teman sebaya dengan pengawasan guru.',
                    'sedang'
                ),

                'siber' => $this->addRule(
                    $aturan,
                    'Keamanan Digital dan Dokumentasi Bukti',
                    'Bantu siswa menyimpan bukti digital, mengamankan akun, memblokir atau melaporkan akun pelaku, serta menghindari penyebaran ulang konten yang merugikan.',
                    'tinggi'
                ),

                default => null,
            };
        }

        /*
         * Guru menyatakan siswa memerlukan tindak lanjut.
         */
        if (
            $observasi
            && $observasi->perlu_tindak_lanjut === 'ya'
        ) {
            $this->addRule(
                $aturan,
                'Koordinasi Guru BK dan Wali Kelas',
                'Koordinasikan hasil check-in, laporan, dan observasi antara guru BK dan wali kelas. Tentukan penanggung jawab serta jadwal pemantauan berikutnya.',
                'tinggi'
            );
        }

        /*
         * Tekanan emosional terlihat kuat berdasarkan observasi.
         */
        if (
            $observasi
            && (int) $observasi->tekanan >= 3
        ) {
            $this->addRule(
                $aturan,
                'Pemantauan Kondisi Emosional',
                'Pantau perubahan emosi, konsentrasi belajar, kehadiran, serta perilaku menarik diri. Lakukan pendampingan bertahap dan rujukan profesional apabila ditemukan kebutuhan yang berada di luar kewenangan sekolah.',
                'tinggi'
            );
        }

        return array_values($aturan);
    }

    /**
     * Menambahkan aturan sekaligus mencegah duplikasi
     * jenis rekomendasi.
     */
    private function addRule(
        array &$aturan,
        string $jenis,
        string $deskripsi,
        string $prioritas
    ): void {
        $aturan[$jenis] = [
            'jenis_rekomendasi' => $jenis,
            'deskripsi' => $deskripsi,
            'prioritas' => $prioritas,
        ];
    }
}
