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
        Schema::create('agenda_pancasila', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('id_agenda');
            $table->foreignUuid('id_guru');
            $table->date('tanggal');
            $table->foreignUuid('id_siswa');
            $table->integer('dimensi')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('semester');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_pancasila');
    }
};
