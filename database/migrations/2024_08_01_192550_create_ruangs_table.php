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
        Schema::create('sapras_ruang', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('kode')->nullable();
            $table->string('nama')->nullable();
            $table->string('warna')->nullable();
            $table->string('umum')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sapras_ruang');
    }
};
