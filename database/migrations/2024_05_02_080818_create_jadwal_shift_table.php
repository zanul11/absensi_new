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
        Schema::create('jadwal_shift', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->time('jam_keluar_istirahat');
            $table->time('jam_masuk_istirahat');
            $table->string('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_shift');
    }
};
