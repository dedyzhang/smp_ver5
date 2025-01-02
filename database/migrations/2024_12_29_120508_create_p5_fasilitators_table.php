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
        Schema::create('p5_fasilitator', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('id_proyek')->nullable();
            $table->foreignUuid('id_guru')->nullable();
            $table->foreignUuid('id_kelas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p5_fasilitator');
    }
};
