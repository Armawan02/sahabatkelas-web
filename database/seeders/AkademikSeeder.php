<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Siswa;

class AkademikSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Kelas
        $kelas1 = Kelas::create(['nama_kelas' => 'X RPL 1', 'tingkat' => 'X', 'jurusan' => 'RPL', 'tahun_ajaran' => '2025/2026']);
        $kelas2 = Kelas::create(['nama_kelas' => 'X RPL 2', 'tingkat' => 'X', 'jurusan' => 'RPL', 'tahun_ajaran' => '2025/2026']);

        // Ambil data User berdasarkan Email
        $userGuruBk = User::where('email', 'gurubk@gmail.com')->first();
        $userWali = User::where('email', 'walikelas@gmail.com')->first();
        $userSiswa1 = User::where('email', 'siswa1@gmail.com')->first();
        $userSiswa2 = User::where('email', 'siswa2@gmail.com')->first();
        $userSiswa3 = User::where('email', 'siswa3@gmail.com')->first();

        // 2. Profil Guru
        $guruBk = Guru::create(['id_user' => $userGuruBk->id_user, 'nip' => '198001012005011001', 'nama' => 'Drs. Budi Santoso', 'jabatan' => 'Guru BK', 'status' => 'aktif']);
        $waliKelas = Guru::create(['id_user' => $userWali->id_user, 'nip' => '198502022010012002', 'nama' => 'Siti Aminah, S.Kom', 'jabatan' => 'Wali Kelas X RPL 1', 'status' => 'aktif']);

        // 3. Profil Siswa
        Siswa::create(['id_user' => $userSiswa1->id_user, 'id_kelas' => $kelas1->id_kelas, 'nis' => '25261001', 'nama' => 'Ahmad Reza', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2010-05-14', 'status' => 'aktif']);
        Siswa::create(['id_user' => $userSiswa2->id_user, 'id_kelas' => $kelas1->id_kelas, 'nis' => '25261002', 'nama' => 'Nisa Salsabila', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '2010-08-21', 'status' => 'aktif']);
        Siswa::create(['id_user' => $userSiswa3->id_user, 'id_kelas' => $kelas2->id_kelas, 'nis' => '25261003', 'nama' => 'Fajar Pratama', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2010-12-02', 'status' => 'aktif']);
    }
}