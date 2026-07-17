<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckIn;
use App\Services\RiskCalculationService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckInController extends Controller
{
    public function create()
    {
        $startOfWeek = Carbon::now()
            ->locale('id')
            ->startOfWeek();

        $endOfWeek = Carbon::now()
            ->locale('id')
            ->endOfWeek();

        $periode =
            $startOfWeek->translatedFormat('d F Y')
            . ' - '
            . $endOfWeek->translatedFormat('d F Y');

        return view(
            'siswa.check_in',
            compact('periode')
        );
    }

    public function store(Request $request, RiskCalculationService $riskService)
    {
        $validated = $request->validate([
            'perasaan' => 'required|in:sangat_tidak_baik,kurang_baik,biasa_saja,baik,sangat_baik',
            'rasa_aman' => 'required|in:0,1,2,3,4',
            'diterima_teman' => 'required|in:0,1,2,3,4',
            'kenyamanan_belajar' => 'required|in:0,1,2,3,4',
            'gangguan_teman' => 'required|in:0,1,2,3,4',
            'melihat_bullying' => 'required|in:ya,tidak',
            'teman_diskusi' => 'required|in:ada,tidak_ada',
            'ingin_dibantu' => 'required|in:ya_mendesak,ya_biasa,tidak',
            'komentar' => 'nullable|string'
        ]);

        $siswa = Auth::user()->siswa;

        $validated['id_siswa'] = $siswa->id_siswa;
        $validated['tanggal'] = Carbon::now()->toDateString();
        $validated['status'] = 'selesai';

        // Simpan data check-in
        CheckIn::create($validated);

        // Hitung ulang skor risiko (Event-Driven)
        $riskService->recalculateRisk($siswa->id_siswa);

        return redirect()->route('siswa.beranda')->with('success', 'Check-in berhasil dikirim. Terima kasih sudah menceritakan kondisi kamu. Jawabanmu akan membantu pihak sekolah memberikan pendampingan yang sesuai.');
    }
}