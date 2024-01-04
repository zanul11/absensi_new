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
        Schema::table('request_absen_pulang', function (Blueprint $table) {
            $table->tinyInteger('jenis')->default(1)->comment("1:Pulang, 2:keluar istirahat, 3:masuk istirahat");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_absen_pulang', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
};
