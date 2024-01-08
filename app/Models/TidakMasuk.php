<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class TidakMasuk extends Model implements HasMedia
{
    use HasFactory,HasUuids, InteractsWithMedia;
    protected $guarded = [];
    protected $table = 'tidak_masuk';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
    public function jenis_izin()
    {
        return $this->belongsTo(JenisIzin::class, 'jenis_izin_id');
    }
}
