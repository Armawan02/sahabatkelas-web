<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\SafeReport;
use App\Models\Siswa;
use App\Models\TindakLanjut;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard utama admin.
     */
    public function index(): View
    {
        $awalMinggu = now()
            ->startOfWeek()
            ->toDateString();

        $akhirMinggu = now()
            ->endOfWeek()
            ->toDateString();

        /*
         * Ringkasan data pengguna dan sekolah.
         */
        $ringkasan = [
            'total_pengguna' => User::query()->count(),

            'total_admin' => User::query()
                ->where('role', 'admin')
                ->count(),

            'total_siswa' => Siswa::query()->count(),

            'total_guru' => Guru::query()->count(),

            'total_kelas' => Kelas::query()->count(),

            'checkin_minggu_ini' => CheckIn::query()
                ->whereBetween(
                    'tanggal',
                    [
                        $awalMinggu,
                        $akhirMinggu,
                    ]
                )
                ->distinct()
                ->count('id_siswa'),

            /*
             * Admin hanya melihat jumlah laporan,
             * bukan isi laporan sensitif siswa.
             */
            'laporan_menunggu' => SafeReport::query()
                ->where('status', 'menunggu')
                ->count(),

            'tindak_lanjut_aktif' => TindakLanjut::query()
                ->where('status', 'proses')
                ->count(),
        ];

        /*
         * Lima akun terbaru.
         */
        $penggunaTerbaru = User::query()
            ->with([
                'siswa',
                'guru',
            ])
            ->latest('created_at')
            ->limit(5)
            ->get();

        /*
         * Ringkasan jumlah pengguna setiap peran.
         */
        $distribusiPeran = [
            'admin' => User::query()
                ->where('role', 'admin')
                ->count(),

            'guru' => User::query()
                ->where('role', 'guru')
                ->count(),

            'siswa' => User::query()
                ->where('role', 'siswa')
                ->count(),
        ];

        return view(
            'admin.dashboard',
            compact(
                'ringkasan',
                'penggunaTerbaru',
                'distribusiPeran'
            )
        );
    }
}
