<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafeReport extends Model
{
    protected $table = 'safe_report';
    protected $primaryKey = 'id_report';

    protected $fillable = [
        'id_siswa',
        'pelapor',
        'jenis',
        'lokasi',
        'waktu',
        'berulang',
        'rasa_tidak_aman',
        'saksi',
        'anonim',
        'prioritas',
        'komentar',
        'status'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    // Jika dipanggil, langsung mendapatkan hasil analisis NLP-nya (relasi via AnalisisResiko)
    public function analisisResiko()
    {
        return $this->hasMany(AnalisisResiko::class, 'id_report', 'id_report');
    }
}