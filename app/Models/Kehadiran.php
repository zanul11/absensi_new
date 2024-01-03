<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Kehadiran extends Model implements HasMedia
{
    use HasFactory, HasUuids, InteractsWithMedia;
    protected $guarded = [];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function jenis_izin()
    {
        return $this->belongsTo(JenisIzin::class, 'jenis_izin_id');
    }
}
