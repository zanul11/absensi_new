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
        Schema::table('absensi', function (Blueprint $table) {
            $table->time('jam_keluar_istirahat')->nullable();
            $table->time('jam_masuk_istirahat')->nullable();
            $table->boolean('is_telat_kembali')->default(0)->comment("Status Telat kembali dari istirahat, 0:tidak, 1:ya");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn('jam_keluar_istirahat');
            $table->dropColumn('jam_masuk_istirahat');
            $table->dropColumn('is_telat_kembali');
        });
    }
};
