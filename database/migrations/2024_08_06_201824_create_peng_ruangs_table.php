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
        Schema::create('sapras_penggunaan', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('tanggal');
            $table->foreignUuid('id_ruang')->nullable();
            $table->foreignUuid('id_jadwal')->nullable();
            $table->foreignUuid('id_waktu')->nullable();
            $table->foreignUuid('id_guru')->nullable();
            $table->foreignUuid('id_kelas')->nullable();
            $table->foreignUuid('id_pelajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sapras_penggunaan');
    }
};
