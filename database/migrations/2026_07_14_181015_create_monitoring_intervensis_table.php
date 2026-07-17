<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'monitoring_intervensi',
            function (Blueprint $table) {
                $table->id('id_monitoring');

                $table->unsignedBigInteger(
                    'id_tindak_lanjut'
                );

                $table->unsignedBigInteger(
                    'id_guru'
                );

                $table->date(
                    'tanggal_monitoring'
                );

                /*
                 * Nilai 1–4.
                 * Semakin tinggi berarti semakin baik.
                 */
                $table->unsignedTinyInteger(
                    'perasaan_aman'
                )->nullable();

                $table->unsignedTinyInteger(
                    'interaksi_sosial'
                )->nullable();

                $table->unsignedTinyInteger(
                    'keterlibatan_belajar'
                )->nullable();

                $table->enum(
                    'hasil_evaluasi',
                    [
                        'membaik',
                        'tetap',
                        'memburuk',
                    ]
                );

                $table->text(
                    'catatan_siswa'
                )->nullable();

                $table->text(
                    'catatan_guru'
                );

                $table->enum(
                    'tindakan_berikutnya',
                    [
                        'lanjut_monitoring',
                        'tindakan_tambahan',
                        'rujuk',
                        'selesai',
                    ]
                );

                /*
                 * Menyimpan snapshot risiko ketika
                 * monitoring dilakukan.
                 */
                $table->decimal(
                    'skor_risiko',
                    5,
                    2
                )->nullable();

                $table->enum(
                    'kategori_risiko',
                    [
                        'rendah',
                        'sedang',
                        'tinggi',
                    ]
                )->nullable();

                $table->timestamps();

                $table->foreign(
                    'id_tindak_lanjut'
                )
                    ->references(
                        'id_tindak_lanjut'
                    )
                    ->on('tindak_lanjut')
                    ->cascadeOnDelete();

                $table->foreign(
                    'id_guru'
                )
                    ->references('id_guru')
                    ->on('guru')
                    ->restrictOnDelete();

                $table->index([
                    'id_tindak_lanjut',
                    'tanggal_monitoring',
                ]);
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'monitoring_intervensi'
        );
    }
};
