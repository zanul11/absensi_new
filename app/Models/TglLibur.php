<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TglLibur extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];
    protected $table = 'tgl_libur';
}
