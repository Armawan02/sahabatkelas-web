<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = Hash::make('12345678');

        // 1. Admin
        User::create(['email' => 'admin@gmail.com', 'password' => $password, 'role' => 'admin', 'status' => 'aktif']);

        // 2. Guru
        User::create(['email' => 'gurubk@gmail.com', 'password' => $password, 'role' => 'guru', 'status' => 'aktif']);
        User::create(['email' => 'walikelas@gmail.com', 'password' => $password, 'role' => 'guru', 'status' => 'aktif']);

        // 3. Siswa
        User::create(['email' => 'siswa1@gmail.com', 'password' => $password, 'role' => 'siswa', 'status' => 'aktif']);
        User::create(['email' => 'siswa2@gmail.com', 'password' => $password, 'role' => 'siswa', 'status' => 'aktif']);
        User::create(['email' => 'siswa3@gmail.com', 'password' => $password, 'role' => 'siswa', 'status' => 'aktif']);
    }
}