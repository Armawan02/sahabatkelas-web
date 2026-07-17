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
        Schema::create('tindak_lanjut', function (Blueprint $table) {
            $table->id('id_tindak_lanjut');
            $table->foreignId('id_analisis')->constrained('analisis_resiko', 'id_analisis')->cascadeOnDelete();
            $table->foreignId('id_guru')->constrained('guru', 'id_guru')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('jenis_tindakan', 100);
            $table->text('catatan');
            $table->text('hasil');
            $table->enum('status', ['proses', 'selesai']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjut');
    }
};
