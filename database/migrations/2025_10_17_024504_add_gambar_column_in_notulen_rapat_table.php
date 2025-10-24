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
        Schema::table('notulen_rapat', function (Blueprint $table) {
            $table->text('dokumentasi')->nullable()->after('guru_hadir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notulen_rapat', function (Blueprint $table) {
            $table->dropColumn('dokumentasi');
        });
    }
};
