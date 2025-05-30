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
        Schema::create('p3_poin', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('tanggal')->nullable();
            $table->foreignUUID('id_siswa');
            $table->string('jenis')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('semester')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p3_poin');
    }
};
