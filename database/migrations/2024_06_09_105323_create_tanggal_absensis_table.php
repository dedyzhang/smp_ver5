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
        Schema::create('absensi_tanggal', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->date('tanggal')->unique();
            $table->boolean('agenda')->nullable();
            $table->boolean('ada_siswa')->nullable();
            $table->integer('semester')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_tanggal');
    }
};
