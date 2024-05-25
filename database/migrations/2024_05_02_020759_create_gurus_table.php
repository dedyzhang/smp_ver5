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
        Schema::create('gurus', function (Blueprint $table) {
            $table->uuid();
            $table->foreignId('id_login');
            $table->string('nama');
            $table->string('nik');
            $table->string('jk');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->text('alamat');
            $table->string('tingkat_studi');
            $table->string('program_studi');
            $table->string('universitas');
            $table->integer('tahun_tamat');
            $table->date('tmt_ngajar');
            $table->date('tmt_smp');
            $table->string('no_telp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
