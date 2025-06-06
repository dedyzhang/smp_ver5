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
        Schema::create('p3_temp', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignuuid('id_siswa');
            $table->string('yang_mengajukan')->nullable();
            $table->foreignuuid('id_pengajuan');
            $table->string('tanggal')->nullable();
            $table->string('jenis')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('status')->nullable();
            $table->string('semester')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p3_temp');
    }
};
