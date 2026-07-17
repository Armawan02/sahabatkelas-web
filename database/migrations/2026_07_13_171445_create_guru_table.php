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
        Schema::create('guru', function (Blueprint $table) {
            $table->id('id_guru');
            $table->foreignId('id_user')->constrained('users', 'id_user')->cascadeOnDelete();
            $table->string('nip', 30)->nullable();
            $table->string('nama', 100);
            $table->string('no_hp', 20)->nullable();
            $table->string('jabatan', 50);
            $table->enum('status', ['aktif', 'nonaktif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
