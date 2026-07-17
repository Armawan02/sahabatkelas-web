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
        Schema::create('hasil_nlp', function (Blueprint $table) {
            $table->id('id_hasil_nlp');
            $table->foreignId('id_analisis')->constrained('analisis_resiko', 'id_analisis')->cascadeOnDelete();
            $table->enum('sumber_data', ['check_in', 'safe_report']);
            $table->text('teks_asli');
            $table->text('teks_preprocessing')->nullable();
            $table->enum('emosi_dominan', ['takut', 'sedih', 'marah', 'cemas', 'netral']);
            $table->decimal('tingkat_emosi', 5, 2);
            $table->enum('indikasi_perundungan', ['ya', 'tidak']);
            $table->decimal('confidence_indikasi', 5, 2);
            $table->text('kata_kunci')->nullable();
            $table->enum('intensitas', ['rendah', 'sedang', 'tinggi']);
            $table->decimal('skor_nlp', 5, 2);
            $table->text('hasil_ringkasan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_nlp');
    }
};
