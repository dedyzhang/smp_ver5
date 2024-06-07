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
        Schema::create('nilai_jabar_mandarin', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('id_ngajar');
            $table->foreignUuid('id_siswa');
            $table->integer('semester')->nullable();
            $table->integer('listening')->nullable();
            $table->integer('speaking')->nullable();
            $table->integer('writing')->nullable();
            $table->integer('reading')->nullable();
            $table->integer('vocabulary')->nullable();
            $table->integer('singing')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_jabar_mandarin');
    }
};
