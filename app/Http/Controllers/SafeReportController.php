<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SafeReport;
use App\Jobs\ProcessIndoBertNlp;
use App\Services\RiskCalculationService;
use Illuminate\Support\Facades\Auth;

class SafeReportController extends Controller
{
    /**
     * Menampilkan halaman form Safe Report untuk siswa
     */
    public function create()
    {
        return view('siswa.safe_report'); // Kita akan membuat tampilan ini nanti
    }

    /**
     * Menyimpan data dan memicu AI
     */
    public function store(Request $request, RiskCalculationService $riskService)
    {
        // 1. Validasi input sesuai dengan tipe data ENUM di database
        $validated = $request->validate([
            'pelapor' => 'required|in:korban,saksi',
            'jenis' => 'required|in:fisik,verbal,sosial,siber',
            'lokasi' => 'required|in:lingkungan_sekolah,luar_sekolah,dunia_maya',
            'waktu' => 'required|in:jam_pelajaran,istirahat,pulang_sekolah',
            'berulang' => 'required|in:ya,tidak',
            'rasa_tidak_aman' => 'required|in:ya,tidak',
            'saksi' => 'required|in:ada,tidak_ada',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'komentar' => 'required|string|min:10', // Minimal 10 karakter agar NLP bisa bekerja optimal
        ]);

        // 2. Dapatkan ID Siswa dari relasi User yang sedang login
        $user = Auth::user();
        $siswa = $user->siswa;

        // Tindakan pencegahan jika relasi siswa tidak ditemukan
        if (!$siswa) {
            return back()->withErrors(['Data profil siswa Anda tidak ditemukan di sistem.']);
        }

        // 3. Tambahkan parameter otomatis
        $validated['id_siswa'] = $siswa->id_siswa;
        $validated['status'] = 'menunggu';
        // Checkbox anonim (jika diceklis nilainya true, jika tidak false)
        $validated['anonim'] = $request->has('anonim') ? true : false;

        // 4. Simpan ke database
        $report = SafeReport::create($validated);

        // 5. Hitung Risiko Awal secara instan
        // Jika prioritas='tinggi' atau rasa_tidak_aman='ya', fungsi ini akan langsung menandai Risiko Tinggi
        $riskService->recalculateRisk($siswa->id_siswa);

        // 6. Lempar teks ke AI secara Asynchronous
        // Proses ini akan berjalan di belakang layar
        ProcessIndoBertNlp::dispatch($report);

        // 7. Kembalikan respons ke siswa seketika
        return redirect('/siswa/beranda')->with('success', 'Laporan berhasil dikirim secara aman dan akan segera ditindaklanjuti. Terima kasih atas keberanianmu.');
    }
}