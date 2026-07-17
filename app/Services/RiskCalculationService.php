<?php

namespace App\Services;

use App\Models\AnalisisResiko;
use App\Models\CheckIn;
use App\Models\SafeReport;
use App\Models\Observasi;
use App\Models\HasilNlp;

class RiskCalculationService
{
    /**
     * Bobot sumber data risiko.
     *
     * Safe Report memiliki bobot total 40%.
     * Ketika NLP tersedia, bobot tersebut dibagi menjadi:
     * - data terstruktur Safe Report: 25%
     * - hasil analisis NLP: 15%
     */
    private const WEIGHT_CHECKIN = 0.35;

    private const WEIGHT_REPORT_WITHOUT_NLP = 0.40;

    private const WEIGHT_REPORT_STRUCTURED = 0.25;

    private const WEIGHT_NLP = 0.15;

    private const WEIGHT_OBSERVATION = 0.25;

    /**
     * Fungsi utama untuk menghitung ulang skor risiko hibrida
     */
    /**
     * Menghitung ulang risiko berdasarkan data terbaru siswa.
     */
    public function __construct(
        private readonly RecommendationService $recommendationService
    ) {}
    
    public function recalculateRisk(int $idSiswa): ?AnalisisResiko
    {
        /*
     * Mengambil data terbaru dari setiap sumber.
     */
        $checkIn = CheckIn::query()
            ->where('id_siswa', $idSiswa)
            ->latest('tanggal')
            ->latest('id_checkin')
            ->first();

        $report = SafeReport::query()
            ->where('id_siswa', $idSiswa)
            ->latest('created_at')
            ->latest('id_report')
            ->first();

        $observasi = Observasi::query()
            ->where('id_siswa', $idSiswa)
            ->latest('tanggal')
            ->latest('id_observasi')
            ->first();

        /*
     * Menghitung setiap komponen terlebih dahulu.
     *
     * Penting:
     * Perhitungan dilakukan sebelum pemeriksaan kondisi darurat
     * agar skor komponen tetap tersimpan.
     */
        $skorCheckIn = $checkIn
            ? $this->calculateCheckInScore($checkIn)
            : null;

        $skorSafeReport = $report
            ? $this->calculateReportScore($report)
            : null;

        $skorObservasi = $observasi
            ? $this->calculateObservationScore($observasi)
            : null;

        /*
     * Mengambil skor NLP terbaru jika proses NLP sudah selesai.
     */
        $skorNlp = $this->getLatestNlpScore($report);

        /*
     * Untuk tahap pertama, skor akhir masih menggunakan
     * tiga sumber terstruktur seperti service sebelumnya.
     *
     * Pemisahan bobot NLP akan dilakukan pada tahap berikutnya.
     */
        $skorAkhir = $this->calculateFinalScore(
            $skorCheckIn,
            $skorSafeReport,
            $skorObservasi,
            $skorNlp
        );

        /*
     * Tidak membuat analisis apabila belum ada sumber data.
     */
        if ($skorAkhir === null) {
            return null;
        }

        /*
     * Kondisi darurat mengubah kategori menjadi tinggi,
     * tetapi tidak menghilangkan skor komponennya.
     */
        if ($this->hasEmergencyIndicators(
            $checkIn,
            $report,
            $observasi
        )) {
            $kategori = 'tinggi';
        } else {
            $kategori = $this->determineRiskCategory(
                $skorAkhir,
                $skorCheckIn,
                $skorSafeReport,
                $skorObservasi
            );
        }

        $analisis = $this->saveAnalisis(
            idSiswa: $idSiswa,
            checkIn: $checkIn,
            report: $report,
            observasi: $observasi,
            skorCheckIn: $skorCheckIn,
            skorSafeReport: $skorSafeReport,
            skorObservasi: $skorObservasi,
            skorNlp: $skorNlp,
            skorAkhir: $skorAkhir,
            kategori: $kategori
        );

        /*
 * Membuat atau memperbarui rekomendasi otomatis
 * berdasarkan hasil analisis terbaru.
 */
        $this->recommendationService->generate($analisis);

        /*
 * Memuat rekomendasi agar langsung tersedia
 * pada objek hasil analisis.
 */
        return $analisis->load('rekomendasi');
    }

    /**
     * Mengambil skor NLP terbaru yang terkait
     * dengan Safe Report terbaru.
     */
    private function getLatestNlpScore(
        ?SafeReport $report
    ): ?float {
        if ($report === null) {
            return null;
        }

        $hasilNlp = HasilNlp::query()
            ->where('sumber_data', 'safe_report')
            ->whereHas(
                'analisisResiko',
                function ($query) use ($report) {
                    $query->where(
                        'id_report',
                        $report->id_report
                    );
                }
            )
            ->latest('id_hasil_nlp')
            ->first();

        if ($hasilNlp === null) {
            return null;
        }

        return round(
            (float) $hasilNlp->skor_nlp,
            2
        );
    }
    /**
     * Logika override untuk kondisi darurat
     */
    /**
     * Memeriksa indikator yang membutuhkan
     * perhatian atau tindak lanjut segera.
     */
    private function hasEmergencyIndicators(
        ?CheckIn $c,
        ?SafeReport $r,
        ?Observasi $o
    ): bool {
        if (
            $c
            && $c->ingin_dibantu === 'ya_mendesak'
        ) {
            return true;
        }

        if (
            $r
            && (
                $r->prioritas === 'tinggi'
                || $r->rasa_tidak_aman === 'ya'
            )
        ) {
            return true;
        }

        if (
            $o
            && (
                $o->perlu_tindak_lanjut === 'ya'
                || (int) $o->agresif === 4
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * Hitung Skor Gabungan dengan Bobot Dinamis
     */
    /**
     * Menggabungkan seluruh komponen skor dengan bobot dinamis.
     *
     * Jika NLP belum tersedia, bobot Safe Report menjadi 40%.
     * Jika NLP tersedia, bobot laporan dibagi menjadi:
     * - Safe Report terstruktur: 25%
     * - NLP: 15%
     *
     * Apabila salah satu sumber belum tersedia, bobot sumber
     * yang tersedia akan dinormalisasi secara otomatis.
     */
    private function calculateFinalScore(
        ?float $skorCheckIn,
        ?float $skorSafeReport,
        ?float $skorObservasi,
        ?float $skorNlp
    ): ?float {
        $totalNilaiBobot = 0.0;
        $totalBobot = 0.0;

        /*
     * Komponen Check-in.
     */
        if ($skorCheckIn !== null) {
            $totalNilaiBobot +=
                self::WEIGHT_CHECKIN * $skorCheckIn;

            $totalBobot += self::WEIGHT_CHECKIN;
        }

        /*
     * Komponen Safe Report.
     *
     * Apabila NLP belum selesai, seluruh bobot laporan
     * diberikan kepada Safe Report terstruktur.
     */
        if ($skorSafeReport !== null) {
            $bobotSafeReport = $skorNlp !== null
                ? self::WEIGHT_REPORT_STRUCTURED
                : self::WEIGHT_REPORT_WITHOUT_NLP;

            $totalNilaiBobot +=
                $bobotSafeReport * $skorSafeReport;

            $totalBobot += $bobotSafeReport;
        }

        /*
     * Komponen NLP.
     */
        if ($skorNlp !== null) {
            $totalNilaiBobot +=
                self::WEIGHT_NLP * $skorNlp;

            $totalBobot += self::WEIGHT_NLP;
        }

        /*
     * Komponen Observasi Guru.
     */
        if ($skorObservasi !== null) {
            $totalNilaiBobot +=
                self::WEIGHT_OBSERVATION * $skorObservasi;

            $totalBobot += self::WEIGHT_OBSERVATION;
        }

        /*
     * Tidak ada data yang dapat dihitung.
     */
        if ($totalBobot <= 0) {
            return null;
        }

        $skorAkhir = $totalNilaiBobot / $totalBobot;

        return round(
            min(100, max(0, $skorAkhir)),
            2
        );
    }

    /**
     * Aturan Penentuan Status Risiko
     */
    private function determineRiskCategory(float $skor, ?float $C, ?float $N, ?float $O): string
    {
        $availableSources = ($C !== null ? 1 : 0) + ($N !== null ? 1 : 0) + ($O !== null ? 1 : 0);

        if ($skor >= 70) {
            return $availableSources >= 2 ? 'tinggi' : 'sedang'; // 'sedang' (perlu verifikasi) jika hanya 1 sumber
        }

        if ($skor >= 35) {
            return 'sedang';
        }

        return 'rendah';
    }

    /**
     * Contoh perhitungan parsial CheckIn (Bisa disesuaikan detail array-nya nanti)
     */
    /**
     * Menghitung skor risiko dari Check-in siswa.
     *
     * Seluruh nilai dinormalisasi ke rentang 0–1,
     * kemudian dikonversi menjadi skor 0–100.
     */
    private function calculateCheckInScore(CheckIn $c): float
    {
        /*
     * Perasaan:
     * semakin buruk perasaan siswa,
     * semakin tinggi nilai risikonya.
     */
        $skorPerasaan = match ($c->perasaan) {
            'sangat_baik' => 0.00,
            'baik' => 0.25,
            'biasa_saja' => 0.50,
            'kurang_baik' => 0.75,
            'sangat_tidak_baik' => 1.00,
            default => 0.00,
        };

        /*
     * Empat pertanyaan utama sudah menggunakan skala:
     * 0 = tidak pernah
     * 1 = jarang
     * 2 = kadang-kadang
     * 3 = sering
     * 4 = sangat sering
     */
        $skorTidakAman = $this->normalizeScale(
            $c->rasa_aman
        );

        $skorDikucilkan = $this->normalizeScale(
            $c->diterima_teman
        );

        $skorGangguanBelajar = $this->normalizeScale(
            $c->kenyamanan_belajar
        );

        $skorGangguanTeman = $this->normalizeScale(
            $c->gangguan_teman
        );

        /*
     * Melihat perundungan menjadi indikator lingkungan kelas.
     */
        $skorMelihatBullying =
            $c->melihat_bullying === 'ya'
            ? 1.00
            : 0.00;

        /*
     * Tidak memiliki seseorang untuk bercerita
     * dapat meningkatkan kerentanan.
     */
        $skorDukungan =
            $c->teman_diskusi === 'tidak_ada'
            ? 1.00
            : 0.00;

        /*
     * Permintaan bantuan tidak digunakan sebagai diagnosis,
     * tetapi menjadi indikator kebutuhan pendampingan.
     */
        $skorPermintaanBantuan = match ($c->ingin_dibantu) {
            'ya_mendesak' => 1.00,
            'ya_biasa' => 0.50,
            'tidak' => 0.00,
            default => 0.00,
        };

        /*
     * Total bobot = 1.00.
     */
        $score = (
            (0.15 * $skorPerasaan)
            + (0.15 * $skorTidakAman)
            + (0.15 * $skorDikucilkan)
            + (0.15 * $skorGangguanBelajar)
            + (0.20 * $skorGangguanTeman)
            + (0.05 * $skorMelihatBullying)
            + (0.05 * $skorDukungan)
            + (0.10 * $skorPermintaanBantuan)
        ) * 100;

        return round(
            min(100, max(0, $score)),
            2
        );
    }

    /**
     * Mengubah skala 0–4 menjadi nilai 0–1.
     */
    private function normalizeScale(
        int|string|null $value
    ): float {
        $value = (int) $value;

        $value = min(
            4,
            max(0, $value)
        );

        return $value / 4;
    }

    /**
     * Menghitung risiko berdasarkan data terstruktur Safe Report.
     *
     * Analisis teks NLP tidak dihitung di sini karena disimpan
     * secara terpisah pada kolom skor_nlp.
     */
    private function calculateReportScore(SafeReport $r): float
    {
        /*
     * Laporan dari korban langsung memiliki bobot risiko
     * lebih tinggi daripada laporan dari saksi.
     */
        $skorPelapor = match ($r->pelapor) {
            'korban' => 1.00,
            'saksi' => 0.60,
            default => 0.00,
        };

        /*
     * Tingkat potensi dampak berdasarkan jenis perundungan.
     *
     * Nilai ini bersifat aturan awal untuk prototipe
     * dan nantinya perlu divalidasi bersama guru BK.
     */
        $skorJenis = match ($r->jenis) {
            'fisik' => 1.00,
            'siber' => 0.85,
            'sosial' => 0.75,
            'verbal' => 0.65,
            default => 0.00,
        };

        /*
     * Perundungan yang berulang meningkatkan risiko.
     */
        $skorBerulang =
            $r->berulang === 'ya'
            ? 1.00
            : 0.00;

        /*
     * Perasaan tidak aman menjadi indikator penting
     * kebutuhan perlindungan dan pendampingan.
     */
        $skorTidakAman =
            $r->rasa_tidak_aman === 'ya'
            ? 1.00
            : 0.00;

        /*
     * Prioritas laporan menggambarkan urgensi penanganan.
     */
        $skorPrioritas = match ($r->prioritas) {
            'tinggi' => 1.00,
            'sedang' => 0.60,
            'rendah' => 0.30,
            default => 0.00,
        };

        /*
     * Total bobot = 1.00
     *
     * Pelapor             = 15%
     * Jenis perundungan   = 15%
     * Kejadian berulang   = 25%
     * Rasa tidak aman     = 30%
     * Prioritas laporan   = 15%
     */
        $score = (
            (0.15 * $skorPelapor)
            + (0.15 * $skorJenis)
            + (0.25 * $skorBerulang)
            + (0.30 * $skorTidakAman)
            + (0.15 * $skorPrioritas)
        ) * 100;

        return round(
            min(100, max(0, $score)),
            2
        );
    }

    /**
     * Menghitung skor risiko berdasarkan observasi guru.
     *
     * Seluruh indikator menggunakan skala:
     * 0 = tidak terlihat
     * 1 = sedikit terlihat
     * 2 = cukup terlihat
     * 3 = terlihat jelas
     * 4 = sangat terlihat atau ekstrem
     */
    private function calculateObservationScore(
        Observasi $o
    ): float {
        $skorPerubahanPerilaku = $this->normalizeScale(
            $o->perubahan_perilaku
        );

        $skorInteraksi = $this->normalizeScale(
            $o->interaksi
        );

        $skorKenyamanan = $this->normalizeScale(
            $o->kenyamanan
        );

        $skorIsolasi = $this->normalizeScale(
            $o->isolasi
        );

        $skorTekanan = $this->normalizeScale(
            $o->tekanan
        );

        $skorAgresif = $this->normalizeScale(
            $o->agresif
        );

        /*
     * Total bobot = 1.00
     *
     * Perubahan perilaku  = 15%
     * Penurunan interaksi = 10%
     * Kenyamanan belajar  = 10%
     * Isolasi sosial      = 20%
     * Tekanan emosional   = 25%
     * Agresif/tanda fisik = 20%
     */
        $score = (
            (0.15 * $skorPerubahanPerilaku)
            + (0.10 * $skorInteraksi)
            + (0.10 * $skorKenyamanan)
            + (0.20 * $skorIsolasi)
            + (0.25 * $skorTekanan)
            + (0.20 * $skorAgresif)
        ) * 100;

        return round(
            min(100, max(0, $score)),
            2
        );
    }

    /**
     * Menyimpan atau memperbarui data di tabel analisis_resiko
     */
    /**
     * Menyimpan atau memperbarui analisis risiko hari ini.
     */
    private function saveAnalisis(
        int $idSiswa,
        ?CheckIn $checkIn,
        ?SafeReport $report,
        ?Observasi $observasi,
        ?float $skorCheckIn,
        ?float $skorSafeReport,
        ?float $skorObservasi,
        ?float $skorNlp,
        float $skorAkhir,
        string $kategori
    ): AnalisisResiko {
        /*
     * Mencari analisis siswa pada tanggal hari ini.
     * whereDate digunakan karena kolom tanggal_analisis
     * bertipe datetime.
     */
        $analisis = AnalisisResiko::query()
            ->where('id_siswa', $idSiswa)
            ->whereDate(
                'tanggal_analisis',
                now()->toDateString()
            )
            ->first();

        /*
     * Jika analisis hari ini belum ada,
     * buat instance baru.
     */
        if ($analisis === null) {
            $analisis = new AnalisisResiko();

            $analisis->id_siswa = $idSiswa;
            $analisis->tanggal_analisis = now()->startOfDay();
        }

        /*
     * Menyimpan sumber data terbaru.
     */
        $analisis->id_checkin =
            $checkIn?->id_checkin;

        $analisis->id_report =
            $report?->id_report;

        $analisis->id_observasi =
            $observasi?->id_observasi;

        /*
     * Menyimpan rincian skor komponen.
     */
        $analisis->skor_checkin =
            $skorCheckIn !== null
            ? round($skorCheckIn, 2)
            : null;

        $analisis->skor_safe_report =
            $skorSafeReport !== null
            ? round($skorSafeReport, 2)
            : null;

        $analisis->skor_observasi =
            $skorObservasi !== null
            ? round($skorObservasi, 2)
            : null;

        $analisis->skor_nlp =
            $skorNlp !== null
            ? round($skorNlp, 2)
            : null;

        /*
     * Menyimpan skor dan kategori akhir.
     */
        $analisis->skor_akhir =
            round(
                min(100, max(0, $skorAkhir)),
                2
            );

        $analisis->kategori_resiko = $kategori;

        $analisis->save();

        return $analisis->fresh();
    }
}