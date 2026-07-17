<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TindakLanjut extends Model
{
    protected $table = 'tindak_lanjut';
    protected $primaryKey = 'id_tindak_lanjut';

    protected $fillable = [
        'id_analisis',
        'id_guru',
        'tanggal',
        'jenis_tindakan',
        'catatan',
        'hasil',
        'status'
    ];

    public function analisisResiko()
    {
        return $this->belongsTo(AnalisisResiko::class, 'id_analisis', 'id_analisis');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function monitoringIntervensi(): HasMany
    {
        return $this->hasMany(
            MonitoringIntervensi::class,
            'id_tindak_lanjut',
            'id_tindak_lanjut'
        )
            ->orderByDesc('tanggal_monitoring')
            ->orderByDesc('created_at');
    }
}