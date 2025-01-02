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
        Schema::create('p5_proyek_detail', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('id_proyek');
            $table->foreignUuid('id_dimensi');
            $table->foreignUuid('id_elemen');
            $table->foreignUuid('id_subelemen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p5_proyek_detail');
    }
};
