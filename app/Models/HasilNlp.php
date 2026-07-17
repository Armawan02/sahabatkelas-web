<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilNlp extends Model
{
    protected $table = 'hasil_nlp';
    protected $primaryKey = 'id_hasil_nlp';

    protected $fillable = [
        'id_analisis',
        'sumber_data',
        'teks_asli',
        'teks_preprocessing',
        'emosi_dominan',
        'tingkat_emosi',
        'indikasi_perundungan',
        'confidence_indikasi',
        'kata_kunci',
        'intensitas',
        'skor_nlp',
        'hasil_ringkasan'
    ];

    public function analisisResiko()
    {
        return $this->belongsTo(AnalisisResiko::class, 'id_analisis', 'id_analisis');
    }
}