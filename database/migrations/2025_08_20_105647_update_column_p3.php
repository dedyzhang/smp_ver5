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
        Schema::table('p3_kategori', function (Blueprint $table) {
            $table->integer('poin')->nullable()->default(0)->after('deskripsi');
        });
        Schema::table('p3_poin', function (Blueprint $table) {
            $table->integer('poin')->nullable()->default(0)->after('deskripsi');
        });
        Schema::table('p3_temp', function (Blueprint $table) {
            $table->integer('poin')->nullable()->default(0)->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p3_kategori', function (Blueprint $table) {
            $table->dropColumn('poin');
        });
        Schema::table('p3_poin', function (Blueprint $table) {
            $table->dropColumn('poin');
        });
        Schema::table('p3_temp', function (Blueprint $table) {
            $table->integer('poin')->nullable()->default(0)->after('deskripsi');
        });
    }
};
