<?php

namespace App\Imports;

use App\Models\Location;
use App\Models\Pegawai;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPegawai implements ToCollection, WithHeadingRow
{

    public function collection(Collection $collection)
    {
        // dd($collection);
        foreach ($collection as $value) {
            $data[] = [
                'id' => Str::uuid(),
                'nip' => $value['nip'],
                'name' => $value['nama'],
                'username' => $value['nip'],
                'password' => bcrypt($value['nip']),
                'alamat' => $value['alamat'],
                'nohp' => $value['nohp'],
                'user' =>  Auth::user()->name,
                'created_at' => now(),
                'updated_at' => now(),
                'location_id' => Location::first()->id,
            ];
        }
        Pegawai::insert($data);
    }
}
