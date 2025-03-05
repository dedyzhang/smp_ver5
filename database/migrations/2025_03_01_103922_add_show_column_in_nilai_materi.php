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
        Schema::table('nilai_materi', function (Blueprint $table) {
            $table->boolean('show')->nullable()->default(0)->after('tupe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_materi', function (Blueprint $table) {
            $table->dropColumn('show');
        });
    }
};
