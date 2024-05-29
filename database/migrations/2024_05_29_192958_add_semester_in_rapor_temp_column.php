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
        Schema::table('nilai_rapor_temp', function (Blueprint $table) {
            $table->string('semester')->nullable()->after('perubahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_rapor_temp', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
    }
};
