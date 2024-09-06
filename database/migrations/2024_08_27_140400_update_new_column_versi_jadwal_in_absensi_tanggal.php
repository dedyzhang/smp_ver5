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
        Schema::table('absensi_tanggal', function (Blueprint $table) {
            $table->foreignUuid('id_jadwal')->after('semester')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_tanggal', function (Blueprint $table) {
            $table->dropColumn('id_jadwal');
        });
    }
};
