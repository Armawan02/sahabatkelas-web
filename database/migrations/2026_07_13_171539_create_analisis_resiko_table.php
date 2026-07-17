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
        Schema::create('analisis_resiko', function (Blueprint $table) {
            $table->id('id_analisis');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->cascadeOnDelete();

            // Gunakan nullOnDelete() agar data analisis tetap ada meskipun laporan aslinya dihapus
            $table->foreignId('id_checkin')->nullable()->constrained('check_in', 'id_checkin')->nullOnDelete();
            $table->foreignId('id_report')->nullable()->constrained('safe_report', 'id_report')->nullOnDelete();
            $table->foreignId('id_observasi')->nullable()->constrained('observasi', 'id_observasi')->nullOnDelete();

            $table->decimal('skor_checkin', 5, 2)->nullable();
            $table->decimal('skor_safe_report', 5, 2)->nullable();
            $table->decimal('skor_observasi', 5, 2)->nullable();
            $table->decimal('skor_nlp', 5, 2)->nullable();
            $table->decimal('skor_akhir', 5, 2);
            $table->enum('kategori_resiko', ['rendah', 'sedang', 'tinggi']);
            $table->dateTime('tanggal_analisis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_resiko');
    }
};
