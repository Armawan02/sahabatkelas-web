<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    protected $table = 'guru';

    protected $primaryKey = 'id_guru';

    protected $fillable = [
        'id_user',
        'nip',
        'nama',
        'no_hp',
        'jabatan',
        'status',
    ];

    /**
     * Data guru terhubung dengan akun pengguna.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    /**
     * Seorang guru dapat membuat banyak observasi.
     */
    public function observasi(): HasMany
    {
        return $this->hasMany(
            Observasi::class,
            'id_guru',
            'id_guru'
        );
    }

    /**
     * Seorang guru dapat melakukan banyak tindak lanjut.
     */
    public function tindakLanjut(): HasMany
    {
        return $this->hasMany(
            TindakLanjut::class,
            'id_guru',
            'id_guru'
        );
    }

    public function monitoringIntervensi()
    {
        return $this->hasMany(
            MonitoringIntervensi::class,
            'id_guru',
            'id_guru'
        );
    }
}
