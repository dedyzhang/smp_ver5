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
        Schema::create('ngajar', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('id_guru');
            $table->foreignUuid('id_pelajaran');
            $table->foreignUuid('id_kelas');
            $table->integer('kkm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ngajar');
    }
};
