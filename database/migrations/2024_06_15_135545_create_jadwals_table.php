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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('id_jadwal')->nullable();
            $table->foreignUuid('id_hari')->nullable();
            $table->foreignUuid('id_waktu')->nullable();
            $table->string('jenis')->nullable();
            $table->foreignUuid('id_ngajar')->nullable();
            $table->foreignUuid('id_pelajaran')->nullable();
            $table->foreignUuid('id_guru')->nullable();
            $table->foreignUuid('id_kelas')->nullable();
            $table->string('spesial')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
