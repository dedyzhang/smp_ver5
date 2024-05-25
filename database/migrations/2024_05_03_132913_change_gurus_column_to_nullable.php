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
        Schema::table('gurus', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->string('agama')->nullable()->change();
            $table->text('alamat')->nullable()->change();
            $table->string('tingkat_studi')->nullable()->change();
            $table->string('program_studi')->nullable()->change();
            $table->string('universitas')->nullable()->change();
            $table->integer('tahun_tamat')->nullable()->change();
            $table->date('tmt_ngajar')->nullable()->change();
            $table->date('tmt_smp')->nullable()->change();
            $table->string('no_telp')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->string('agama')->nullable(false)->change();
            $table->text('alamat')->nullable(false)->change();
            $table->string('tingkat_studi')->nullable(false)->change();
            $table->string('program_studi')->nullable(false)->change();
            $table->string('universitas')->nullable(false)->change();
            $table->integer('tahun_tamat')->nullable(false)->change();
            $table->date('tmt_ngajar')->nullable(false)->change();
            $table->date('tmt_smp')->nullable(false)->change();
            $table->string('no_telp')->nullable(false)->change();
        });
    }
};
