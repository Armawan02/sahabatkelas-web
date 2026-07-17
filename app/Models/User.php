<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_user'; // Override default 'id'

    protected $fillable = [
        'email',
        'password',
        'role',
        'status'
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi One-to-One ke Guru
    public function guru()
    {
        return $this->hasOne(Guru::class, 'id_user', 'id_user');
    }

    // Relasi One-to-One ke Siswa
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id_user', 'id_user');
    }
}
