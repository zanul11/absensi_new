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
        Schema::create('jadwal_operators', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->string('user');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_operators');
    }
};
