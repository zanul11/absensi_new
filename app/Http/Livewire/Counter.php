<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\Pegawai;
use Livewire\Component;

class Counter extends Component
{
    public $lokasis = [];
    public $locationId;

    public $pegawais = [];
    public $pegawaiId;

    protected $listeners = [
        'ubahLokasi' =>  'changedLocation'
    ];

    public function mount()
    {
        $this->lokasis = Location::all();
        if (!is_null($this->locationId))
            $this->pegawais = Pegawai::query()->where('location_id', $this->locationId)->get();
    }
    public function hydrate()
    {
        $this->emit('reinit', 'tes');
    }
    public function render()
    {
        // $this->emit('reinit');
        return view('livewire.counter');
    }

    public function like()
    {
        dd('like');
    }

    public function changedLocation()
    {
        $this->emit('kise', 'tes');
    }

    // updatedLocationId


    public function updatedLocationId($value)
    {
        // return $value;
        $this->pegawais = [];
        if (isset($value))
            $this->pegawais = Pegawai::query()->where('location_id', $value)->get();
    }
}
