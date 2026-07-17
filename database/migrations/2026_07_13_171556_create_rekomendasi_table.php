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
        Schema::create('rekomendasi', function (Blueprint $table) {
            $table->id('id_rekomendasi');
            $table->foreignId('id_analisis')->constrained('analisis_resiko', 'id_analisis')->cascadeOnDelete();
            $table->string('jenis_rekomendasi', 100);
            $table->text('deskripsi');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi']);
            $table->enum('status', ['menunggu', 'diterapkan', 'diabaikan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekomendasi');
    }
};
