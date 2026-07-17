<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('safe_report', function (Blueprint $table) {
            $table->id('id_report');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->cascadeOnDelete();
            $table->enum('pelapor', ['korban', 'saksi']);
            $table->enum('jenis', ['fisik', 'verbal', 'sosial', 'siber']);
            $table->enum('lokasi', ['lingkungan_sekolah', 'luar_sekolah', 'dunia_maya']);
            $table->enum('waktu', ['jam_pelajaran', 'istirahat', 'pulang_sekolah']);
            $table->enum('berulang', ['ya', 'tidak']);
            $table->enum('rasa_tidak_aman', ['ya', 'tidak']);
            $table->enum('saksi', ['ada', 'tidak_ada']);
            $table->boolean('anonim')->default(false);
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi']);
            $table->text('komentar')->nullable();
            $table->enum('status', ['menunggu', 'diproses', 'selesai']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safe_report');
    }
};
