<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnalisisResiko extends Model
{
    protected $table = 'analisis_resiko';

    protected $primaryKey = 'id_analisis';

    protected $fillable = [
        'id_siswa',
        'id_checkin',
        'id_report',
        'id_observasi',
        'skor_checkin',
        'skor_safe_report',
        'skor_observasi',
        'skor_nlp',
        'skor_akhir',
        'kategori_resiko',
        'tanggal_analisis',
    ];

    protected $casts = [
        'skor_checkin' => 'float',
        'skor_safe_report' => 'float',
        'skor_observasi' => 'float',
        'skor_nlp' => 'float',
        'skor_akhir' => 'float',
        'tanggal_analisis' => 'datetime',
    ];

    /**
     * Analisis dimiliki oleh seorang siswa.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(
            Siswa::class,
            'id_siswa',
            'id_siswa'
        );
    }

    /**
     * Check-in yang digunakan dalam analisis.
     */
    public function checkIn(): BelongsTo
    {
        return $this->belongsTo(
            CheckIn::class,
            'id_checkin',
            'id_checkin'
        );
    }

    /**
     * Safe Report yang digunakan dalam analisis.
     */
    public function safeReport(): BelongsTo
    {
        return $this->belongsTo(
            SafeReport::class,
            'id_report',
            'id_report'
        );
    }

    /**
     * Observasi guru yang digunakan dalam analisis.
     */
    public function observasi(): BelongsTo
    {
        return $this->belongsTo(
            Observasi::class,
            'id_observasi',
            'id_observasi'
        );
    }

    /**
     * Satu analisis dapat memiliki beberapa hasil NLP.
     *
     * Misalnya hasil NLP dari check-in dan Safe Report.
     */
    public function hasilNlp(): HasMany
    {
        return $this->hasMany(
            HasilNlp::class,
            'id_analisis',
            'id_analisis'
        );
    }

    /**
     * Satu analisis dapat menghasilkan beberapa rekomendasi.
     */
    public function rekomendasi(): HasMany
    {
        return $this->hasMany(
            Rekomendasi::class,
            'id_analisis',
            'id_analisis'
        );
    }

    /**
     * Satu analisis dapat memiliki beberapa tindak lanjut.
     */
    public function tindakLanjut(): HasMany
    {
        return $this->hasMany(
            TindakLanjut::class,
            'id_analisis',
            'id_analisis'
        );
    }
}
