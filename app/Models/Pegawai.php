<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pegawai extends Authenticatable
{
    use HasFactory, HasUuids;
    protected $guard = 'pegawai';
    protected $guarded = [];

    public function lokasi()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
