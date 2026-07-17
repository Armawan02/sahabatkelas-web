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
        Schema::create('observasi', function (Blueprint $table) {
            $table->id('id_observasi');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->cascadeOnDelete();
            $table->foreignId('id_guru')->constrained('guru', 'id_guru')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('perubahan_perilaku', ['0', '1', '2', '3', '4']);
            $table->enum('interaksi', ['0', '1', '2', '3', '4']);
            $table->enum('kenyamanan', ['0', '1', '2', '3', '4']);
            $table->enum('isolasi', ['0', '1', '2', '3', '4']);
            $table->enum('tekanan', ['0', '1', '2', '3', '4']);
            $table->enum('agresif', ['0', '1', '2', '3', '4']);
            $table->enum('perlu_tindak_lanjut', ['ya', 'tidak']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observasi');
    }
};
