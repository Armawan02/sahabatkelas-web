<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'id_user',
        'id_kelas',
        'nis',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(
            Kelas::class,
            'id_kelas',
            'id_kelas'
        );
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(
            CheckIn::class,
            'id_siswa',
            'id_siswa'
        );
    }

    public function safeReports(): HasMany
    {
        return $this->hasMany(
            SafeReport::class,
            'id_siswa',
            'id_siswa'
        );
    }

    public function observasi(): HasMany
    {
        return $this->hasMany(
            Observasi::class,
            'id_siswa',
            'id_siswa'
        );
    }

    public function analisisResiko(): HasMany
    {
        return $this->hasMany(
            AnalisisResiko::class,
            'id_siswa',
            'id_siswa'
        );
    }

    /**
     * Mengambil satu analisis risiko terbaru milik siswa.
     */
    public function analisisTerbaru(): HasOne
    {
        return $this->hasOne(
            AnalisisResiko::class,
            'id_siswa',
            'id_siswa'
        )->latestOfMany('tanggal_analisis');
    }
}
