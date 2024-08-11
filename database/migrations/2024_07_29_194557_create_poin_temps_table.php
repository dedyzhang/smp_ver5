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
        Schema::create('poin_temp', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('tanggal')->nullable();
            $table->foreignUuid('id_aturan');
            $table->foreignUuid('id_siswa');
            $table->string('penginput');
            $table->foreignUuid('id_input');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_temp');
    }
};
