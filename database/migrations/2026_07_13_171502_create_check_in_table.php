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
        Schema::create('check_in', function (Blueprint $table) {
            $table->id('id_checkin');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('perasaan', ['sangat_tidak_baik', 'kurang_baik', 'biasa_saja', 'baik', 'sangat_baik']);
            $table->enum('rasa_aman', ['0', '1', '2', '3', '4']);
            $table->enum('diterima_teman', ['0', '1', '2', '3', '4']);
            $table->enum('kenyamanan_belajar', ['0', '1', '2', '3', '4']);
            $table->enum('teman_diskusi', ['ada', 'tidak_ada']);
            $table->enum('gangguan_teman', ['0', '1', '2', '3', '4']);
            $table->enum('melihat_bullying', ['ya', 'tidak']);
            $table->enum('ingin_dibantu', ['ya_mendesak', 'ya_biasa', 'tidak']);
            $table->text('komentar')->nullable();
            $table->enum('status', ['diproses', 'selesai']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_in');
    }
};
