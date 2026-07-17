<?php

namespace App\Jobs;

use App\Models\AnalisisResiko;
use App\Models\HasilNlp;
use App\Models\SafeReport;
use App\Services\IndoBertApiService;
use App\Services\RiskCalculationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class ProcessIndoBertNlp implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Maksimal percobaan job.
     */
    public int $tries = 3;

    /**
     * Batas waktu proses dalam detik.
     */
    public int $timeout = 60;

    /**
     * ID Safe Report yang akan diproses.
     */
    private int $reportId;

    public function __construct(SafeReport $report)
    {
        /*
         * Menyimpan ID lebih aman daripada menyimpan
         * seluruh objek model di dalam queue.
         */
        $this->reportId = $report->id_report;
    }

    /**
     * Jeda pengulangan jika FastAPI gagal dihubungi.
     */
    public function backoff(): array
    {
        return [10, 30, 60];
    }

    /**
     * Memproses teks Safe Report melalui FastAPI.
     */
    public function handle(
        IndoBertApiService $apiService,
        RiskCalculationService $riskService
    ): void {
        $report = SafeReport::query()
            ->find($this->reportId);

        /*
         * Laporan mungkin sudah dihapus sebelum queue diproses.
         */
        if ($report === null) {
            Log::warning(
                'Proses NLP dibatalkan karena Safe Report tidak ditemukan.',
                [
                    'id_report' => $this->reportId,
                ]
            );

            return;
        }

        /*
         * Mengirim teks laporan ke FastAPI.
         */
        $aiResult = $apiService->analyzeText(
            $report->komentar
        );

        /*
         * Throw exception agar Laravel mencoba ulang job.
         */
        if ($aiResult === null) {
            throw new RuntimeException(
                'FastAPI tidak memberikan hasil analisis.'
            );
        }

        /*
         * Memastikan analisis risiko untuk laporan ini sudah ada.
         */
        $analisis = AnalisisResiko::query()
            ->where('id_siswa', $report->id_siswa)
            ->where('id_report', $report->id_report)
            ->latest('tanggal_analisis')
            ->first();

        if ($analisis === null) {
            $analisis = $riskService->recalculateRisk(
                $report->id_siswa
            );
        }

        if ($analisis === null) {
            throw new RuntimeException(
                'Analisis risiko tidak dapat dibuat.'
            );
        }

        /*
         * Mencegah hasil laporan lama ditempelkan
         * pada analisis milik laporan yang berbeda.
         */
        if ((int) $analisis->id_report !== $report->id_report) {
            Log::warning(
                'Safe Report bukan lagi laporan terbaru siswa.',
                [
                    'id_report' => $report->id_report,
                    'id_analisis' => $analisis->id_analisis,
                    'id_report_analisis' => $analisis->id_report,
                ]
            );

            return;
        }

        $kategori = (string) $aiResult['kategori'];

        $confidenceKategori = round(
            (float) $aiResult['confidence'] * 100,
            2
        );

        $probabilitasPerundungan = round(
            (float) $aiResult['probabilitas_perundungan'] * 100,
            2
        );

        /*
         * Ambang awal prototipe:
         * probabilitas perundungan minimal 70%.
         */
        $terindikasiPerundungan =
            $kategori !== 'bukan_perundungan'
            && $probabilitasPerundungan >= 70;

        $intensitas = match (true) {
            $probabilitasPerundungan >= 85 => 'tinggi',
            $probabilitasPerundungan >= 60 => 'sedang',
            default => 'rendah',
        };

        $namaKategori = str_replace(
            '_',
            ' ',
            $kategori
        );

        /*
         * Satu analisis hanya menyimpan satu hasil
         * Safe Report terbaru agar tidak duplikat.
         */
        HasilNlp::query()->updateOrCreate(
            [
                'id_analisis' => $analisis->id_analisis,
                'sumber_data' => 'safe_report',
            ],
            [
                'teks_asli' => $report->komentar,
                'teks_preprocessing' => null,

                /*
                 * FastAPI saat ini belum menganalisis emosi.
                 * Nilai netral dipakai sebagai placeholder.
                 */
                'emosi_dominan' => 'netral',
                'tingkat_emosi' => 0,

                'indikasi_perundungan' =>
                $terindikasiPerundungan
                    ? 'ya'
                    : 'tidak',

                'confidence_indikasi' =>
                $probabilitasPerundungan,

                'kata_kunci' => null,
                'intensitas' => $intensitas,
                'skor_nlp' => $probabilitasPerundungan,

                'hasil_ringkasan' =>
                'Kategori dominan: '
                    . ucfirst($namaKategori)
                    . ' dengan confidence '
                    . number_format(
                        $confidenceKategori,
                        2,
                        ',',
                        '.'
                    )
                    . '%. Probabilitas perundungan: '
                    . number_format(
                        $probabilitasPerundungan,
                        2,
                        ',',
                        '.'
                    )
                    . '%.',
            ]
        );

        /*
         * Menghitung ulang skor dengan komponen NLP.
         * RecommendationService juga akan dijalankan
         * oleh RiskCalculationService.
         */
        $riskService->recalculateRisk(
            $report->id_siswa
        );

        Log::info(
            'Safe Report berhasil dianalisis oleh FastAPI.',
            [
                'id_report' => $report->id_report,
                'id_analisis' => $analisis->id_analisis,
                'kategori' => $kategori,
                'skor_nlp' => $probabilitasPerundungan,
            ]
        );
    }

    /**
     * Mencatat kegagalan setelah seluruh percobaan habis.
     */
    public function failed(Throwable $exception): void
    {
        Log::error(
            'Job ProcessIndoBertNlp gagal diproses.',
            [
                'id_report' => $this->reportId,
                'message' => $exception->getMessage(),
            ]
        );
    }
}
