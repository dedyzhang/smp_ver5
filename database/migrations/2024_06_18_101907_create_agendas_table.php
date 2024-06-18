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
        Schema::create('agenda', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->date('tanggal')->nullable();
            $table->foreignUuid('id_versi')->nullable();
            $table->foreignUuid('id_jadwal')->nullable();
            $table->foreignUuid('id_guru')->nullable();
            $table->text('pembahasan')->nullable();
            $table->text('metode')->nullable();
            $table->string('proses')->nullable();
            $table->text('kegiatan')->nullable();
            $table->text('kendala')->nullable();
            $table->string('validasi')->nullable();
            $table->text('catatan_kepsek')->nullable();
            $table->integer('semester');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};
