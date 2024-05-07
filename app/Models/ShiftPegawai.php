<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftPegawai extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];
    protected $table = 'shift_pegawai';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
    public function shift()
    {
        return $this->belongsTo(JadwalShift::class, 'shift_id');
    }
}
