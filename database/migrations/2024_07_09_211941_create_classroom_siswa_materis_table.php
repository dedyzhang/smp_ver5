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
        Schema::create('classroom_siswa', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('id_classroom');
            $table->foreignUuid('id_siswa');
            $table->string('status')->nullable();
            $table->string('last_seen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_siswa');
    }
};
