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
        Schema::create('nilai_rapor_temp', function (Blueprint $table) {
            $table->uuid();
            $table->foreignUuid('id_ngajar');
            $table->foreignUuid('id_siswa');
            $table->string('jenis')->nullable();
            $table->string('perubahan')->nullable();
            $table->string('semester')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_rapor_temp');
    }
};
