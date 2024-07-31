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
        Schema::table('jadwal_operators', function (Blueprint $table) {
            $table->boolean('is_beda_hari')->default(1)->comment("Jadwal Masuk dan Keluar di beda hari, 0:tidak, 1:ya");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_operators', function (Blueprint $table) {
            $table->dropColumn('is_beda_hari');
        });
    }
};
