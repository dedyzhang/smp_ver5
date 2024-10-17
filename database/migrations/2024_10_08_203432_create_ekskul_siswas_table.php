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
        Schema::create('ekskul_siswa', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('id_ekskul');
            $table->foreignUuid('id_siswa');
            $table->text('deskripsi')->nullable();
            $table->string('semester');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekskul_siswa');
    }
};
