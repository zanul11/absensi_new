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
        Schema::create('request_absen_pulang', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pegawai_id')->constrained('pegawais');
            $table->datetime('tanggal');
            $table->string('keterangan')->nullable();
            $table->tinyInteger('status')->default(0)->comment("Status pengajuan, 0:Request, 1:Diterima, 2:Ditolak");
            $table->string('alasan')->nullable();
            $table->datetime('verified_at')->nullable();
            $table->string('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_absen_pulang');
    }
};
