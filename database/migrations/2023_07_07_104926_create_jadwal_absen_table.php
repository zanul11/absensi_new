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
        Schema::create('jadwal_absen', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->tinyInteger('hari')->comment("0:Minggu, 1:Senin, ..... 6:Sabtu");
            $table->boolean('status')->default(1);
            $table->time('jam_masuk');
            $table->time('jam_masuk_toleransi');
            $table->time('jam_pulang');
            $table->time('jam_pulang_toleransi');
            $table->string('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_absen');
    }
};
