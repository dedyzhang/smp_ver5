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
        Schema::create('sapras_barang', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('id_ruang')->nullable();
            $table->string('barang')->nullable();
            $table->string('merk')->nullable();
            $table->string('penyedia')->nullable();
            $table->string('tanggal')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('jumlah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sapras_barang');
    }
};
