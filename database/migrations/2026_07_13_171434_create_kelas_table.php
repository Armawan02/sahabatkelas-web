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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('nama_kelas', 20);
            $table->string('tingkat', 5);
            $table->string('jurusan', 20);
            $table->string('no_hp', 20)->nullable();
            $table->string('tahun_ajaran', 15);
            $table->timestamps(); // Opsional, best practice di Laravel
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
