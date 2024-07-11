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
        Schema::create('classroom', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('id_bahan')->nullable();
            $table->foreignUuid('id_ngajar');
            $table->string('jenis')->nullable();
            $table->text('judul')->nullable();
            $table->dateTime('tanggal_post')->nullable();
            $table->dateTime('tanggal_due')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('file')->nullable();
            $table->text('link')->nullable();
            $table->text('isi')->nullable();
            $table->boolean('show_nilai')->nullable();
            $table->string('status')->nullable();
            $table->string('token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom');
    }
};
