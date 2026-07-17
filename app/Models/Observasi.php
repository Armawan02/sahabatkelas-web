<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observasi extends Model
{
    protected $table = 'observasi';
    protected $primaryKey = 'id_observasi';

    protected $fillable = [
        'id_siswa',
        'id_guru',
        'tanggal',
        'perubahan_perilaku',
        'interaksi',
        'kenyamanan',
        'isolasi',
        'tekanan',
        'agresif',
        'perlu_tindak_lanjut',
        'catatan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function analisisResiko()
    {
        return $this->hasOne(AnalisisResiko::class, 'id_observasi', 'id_observasi');
    }
}