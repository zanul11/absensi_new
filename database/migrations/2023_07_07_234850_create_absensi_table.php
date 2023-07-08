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
        Schema::create('absensi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pegawai_id')->constrained('pegawais');
            $table->date('tanggal');
            $table->tinyInteger('hari')->comment("1=hari masuk, 0=hari libur");
            $table->boolean('status')->default(1)->comment("Status Masuk, 0:tidak masuk, 1:masuk");
            $table->string('keterangan')->nullable();
            $table->foreignUuid('jenis_izin_id')->nullable()->constrained('jenis_izin');
            $table->time('jam_masuk');
            $table->boolean('is_telat')->default(0)->comment("Status Telat, 0:tidak, 1:ya");
            $table->time('jam_pulang');
            $table->boolean('is_pulang_cepat')->default(0)->comment("Status Pulang Cepat, 0:tidak, 1:ya");
            $table->string('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
