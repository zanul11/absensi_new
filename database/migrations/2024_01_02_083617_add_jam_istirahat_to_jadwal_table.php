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
        Schema::table('jadwal_absen', function (Blueprint $table) {
            $table->time('jam_keluar_istirahat')->nullable();
            $table->time('jam_masuk_istirahat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_absen', function (Blueprint $table) {
            $table->dropColumn('jam_keluar_istirahat');
            $table->dropColumn('jam_masuk_istirahat');
        });
    }
};
