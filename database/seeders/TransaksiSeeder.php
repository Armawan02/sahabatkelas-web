<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\CheckIn;
use App\Models\SafeReport;
use App\Models\Observasi;
use App\Models\AnalisisResiko;
use App\Models\HasilNlp;
use App\Models\Rekomendasi;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    public function run()
    {
        $siswa = Siswa::where('nis', '25261001')->first(); // Ahmad Reza
        $guruBk = Guru::where('jabatan', 'Guru BK')->first();
        $now = Carbon::now();

        // 1. Check-In Siswa (Kondisi Buruk)
        $checkIn = CheckIn::create([
            'id_siswa' => $siswa->id_siswa,
            'tanggal' => $now->toDateString(),
            'perasaan' => 'kurang_baik',
            'rasa_aman' => '1',
            'diterima_teman' => '2',
            'kenyamanan_belajar' => '1',
            'teman_diskusi' => 'tidak_ada',
            'gangguan_teman' => '3',
            'melihat_bullying' => 'ya',
            'ingin_dibantu' => 'ya_mendesak',
            'komentar' => 'Saya merasa cemas saat istirahat.',
            'status' => 'selesai'
        ]);

        // 2. Safe Report (Skenario Cyberbullying & Fisik)
        $report = SafeReport::create([
            'id_siswa' => $siswa->id_siswa,
            'pelapor' => 'korban',
            'jenis' => 'siber',
            'lokasi' => 'luar_sekolah',
            'waktu' => 'pulang_sekolah',
            'berulang' => 'ya',
            'rasa_tidak_aman' => 'ya',
            'saksi' => 'ada',
            'anonim' => false,
            'prioritas' => 'tinggi',
            'komentar' => 'Saya sering dicegat dan diejek saat pulang sekolah di sekitar jalan poros Majene. Mereka juga mengirim pesan ancaman di WA.',
            'status' => 'diproses'
        ]);

        // 3. Observasi Guru
        $observasi = Observasi::create([
            'id_siswa' => $siswa->id_siswa,
            'id_guru' => $guruBk->id_guru,
            'tanggal' => $now->toDateString(),
            'perubahan_perilaku' => '3',
            'interaksi' => '1',
            'kenyamanan' => '1',
            'isolasi' => '3',
            'tekanan' => '4',
            'agresif' => '0',
            'perlu_tindak_lanjut' => 'ya',
            'catatan' => 'Siswa terlihat sangat murung di kelas dan sering menyendiri saat jam istirahat.'
        ]);

        // 4. Analisis Risiko (Kalkulasi Sentral)
        $analisis = AnalisisResiko::create([
            'id_siswa' => $siswa->id_siswa,
            'id_checkin' => $checkIn->id_checkin,
            'id_report' => $report->id_report,
            'id_observasi' => $observasi->id_observasi,
            'skor_checkin' => 85.50, // Dummy score
            'skor_safe_report' => 92.00,
            'skor_observasi' => 88.00,
            'skor_nlp' => 95.50,
            'skor_akhir' => 90.30,
            'kategori_resiko' => 'tinggi',
            'tanggal_analisis' => $now
        ]);

        // 5. Hasil NLP (Simulasi kembalian dari FastAPI IndoBERT)
        HasilNlp::create([
            'id_analisis' => $analisis->id_analisis,
            'sumber_data' => 'safe_report',
            'teks_asli' => $report->komentar,
            'teks_preprocessing' => 'cegat ejek pulang sekolah jalan poros majene ancam wa',
            'emosi_dominan' => 'takut',
            'tingkat_emosi' => 88.50,
            'indikasi_perundungan' => 'ya',
            'confidence_indikasi' => 95.50,
            'kata_kunci' => 'cegat, ejek, ancam, majene',
            'intensitas' => 'tinggi',
            'skor_nlp' => 95.50,
            'hasil_ringkasan' => 'Indikasi perundungan siber dan verbal berulang di luar area sekolah.'
        ]);

        // 6. Rekomendasi Sistem
        Rekomendasi::create([
            'id_analisis' => $analisis->id_analisis,
            'jenis_rekomendasi' => 'Pendampingan Psikologis',
            'deskripsi' => 'Segera jadwalkan sesi konseling dengan siswa. Libatkan wali kelas untuk memantau keamanan saat pulang sekolah.',
            'prioritas' => 'tinggi',
            'status' => 'menunggu'
        ]);
    }
}