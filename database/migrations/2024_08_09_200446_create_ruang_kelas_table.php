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
        Schema::create('sapras_ruang_kelas', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('id_kelas');
            $table->foreignUuid('id_ruang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sapras_ruang_kelas');
    }
};
