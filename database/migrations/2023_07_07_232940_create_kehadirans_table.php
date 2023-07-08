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
        Schema::create('kehadirans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pegawai_id')->constrained('pegawais');
            $table->tinyInteger('jenis')->default(0)->comment("0:Masuk, 1:Pulang");
            $table->date('tanggal');
            $table->foreignUuid('jenis_izin_id')->nullable()->constrained('jenis_izin');
            $table->string('keterangan')->nullable();
            $table->time('jam');
            $table->string('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadirans');
    }
};
