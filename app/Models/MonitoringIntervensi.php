<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringIntervensi extends Model
{
    use HasFactory;

    protected $table = 'monitoring_intervensi';

    protected $primaryKey = 'id_monitoring';

    protected $fillable = [
        'id_tindak_lanjut',
        'id_guru',
        'tanggal_monitoring',
        'perasaan_aman',
        'interaksi_sosial',
        'keterlibatan_belajar',
        'hasil_evaluasi',
        'catatan_siswa',
        'catatan_guru',
        'tindakan_berikutnya',
        'skor_risiko',
        'kategori_risiko',
    ];

    protected $casts = [
        'tanggal_monitoring' => 'date',
        'perasaan_aman' => 'integer',
        'interaksi_sosial' => 'integer',
        'keterlibatan_belajar' => 'integer',
        'skor_risiko' => 'float',
    ];

    public function tindakLanjut(): BelongsTo
    {
        return $this->belongsTo(
            TindakLanjut::class,
            'id_tindak_lanjut',
            'id_tindak_lanjut'
        );
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(
            Guru::class,
            'id_guru',
            'id_guru'
        );
    }
}
