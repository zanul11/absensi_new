<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\Pegawai;
use Livewire\Component;

class LokasiPegawai extends Component
{

    public $pegawais = [];
    public $lokasis = [];

    public $locationId;
    public $daftar_pegawai_lokasi = [];

    public $keySearch;

    public function mount($lokasi)
    {
        $this->locationId = $lokasi;
        $this->lokasis = Location::all();
        $this->pegawais = Pegawai::query()->orderby('name')->get();
        $this->daftar_pegawai_lokasi = Pegawai::query()->where('location_id', $lokasi)->orderby('name')->get();
    }

    public function render()
    {
        return view('livewire.lokasi-pegawai');
    }


    public function ubahLokasi($value)
    {
        $this->locationId = $value;
        $this->daftar_pegawai_lokasi = Pegawai::query()->where('location_id', $value)->orderby('name')->get();
        // dd($value);
    }

    public function updateLokasiPegawai($value)
    {
        Pegawai::where('id', $value)
            ->update([
                'location_id' => $this->locationId
            ]);
        $this->daftar_pegawai_lokasi = Pegawai::query()->where('location_id',  $this->locationId)->orderby('name')->get();
    }
    public function cariPegawai()
    {
        // dd(!isset($this->keySearch));
        if (isset($this->keySearch))
            $this->pegawais = Pegawai::query()->where('name', 'like', '%' . $this->keySearch . '%')->orderby('name')->get();
        else
            $this->pegawais = Pegawai::query()->orderby('name')->get();
        // dd($this->keySearch);
        $this->daftar_pegawai_lokasi = Pegawai::query()->where('location_                                                                                                                                                               id',  $this->locationId)->orderby('name')->get();
    }
}
