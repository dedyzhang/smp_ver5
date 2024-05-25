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
        Schema::create('siswa', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('id_login');
            $table->string('nama');
            $table->string('nis');
            $table->foreignUuid('id_kelas')->nullable();
            $table->string('jk');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama')->nullable();
            $table->text('alamat')->nullable();
            $table->text('no_handphone')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('no_telp_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('no_telp_ibu')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('pekerjaan_wali')->nullable();
            $table->string('no_telp_wali')->nullable();
            $table->string('nisn')->nullable();
            $table->string('sekolah_asal')->nullable();
            $table->string('nama_ijazah')->nullable();
            $table->string('ortu_ijazah')->nullable();
            $table->string('tempat_lahir_ijazah')->nullable();
            $table->date('tanggal_lahir_ijazah')->nullable();
            $table->string('va')->nullable();
            $table->string('spp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
