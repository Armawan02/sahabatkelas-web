<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    protected $table = 'rekomendasi';
    protected $primaryKey = 'id_rekomendasi';

    protected $fillable = [
        'id_analisis',
        'jenis_rekomendasi',
        'deskripsi',
        'prioritas',
        'status'
    ];

    public function analisisResiko()
    {
        return $this->belongsTo(AnalisisResiko::class, 'id_analisis', 'id_analisis');
    }
}