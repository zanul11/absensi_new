<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];
    protected $table = 'absensi';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
    public function jenis_izin()
    {
        return $this->belongsTo(JenisIzin::class, 'jenis_izin_id');
    }
}
