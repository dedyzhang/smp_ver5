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
        Schema::create('nilai_tujuan_pembelajaran', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('id_materi');
            $table->text('tupe')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_tujuan_pembelajaran');
    }
};
