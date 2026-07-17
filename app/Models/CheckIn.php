<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    protected $table = 'check_in';
    protected $primaryKey = 'id_checkin';

    protected $fillable = [
        'id_siswa',
        'tanggal',
        'perasaan',
        'rasa_aman',
        'diterima_teman',
        'kenyamanan_belajar',
        'teman_diskusi',
        'gangguan_teman',
        'melihat_bullying',
        'ingin_dibantu',
        'komentar',
        'status'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function analisisResiko()
    {
        // Relasi ke tabel analisis resiko (jika check-in ini sudah dianalisis)
        return $this->hasOne(AnalisisResiko::class, 'id_checkin', 'id_checkin');
    }
}