<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShiftPegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "pegawai_id" => ["required", "exists:pegawais,id"],
            "tanggal_mulai" => ["date_format:Y-m-d", "string"],
            "tanggal_selesai" => ["date_format:Y-m-d", "string"],
            "shift_id" => ["nullable", "exists:jadwal_shift,id"],
            "keterangan" => ["required", "string"],
            "user" => ["required", "string"],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tanggal_mulai' => date('Y-m-d', strtotime($this->tanggal_mulai)),
            'tanggal_selesai' => date('Y-m-d', strtotime($this->tanggal_selesai)),
            'user' => Auth::user()->name,
        ]);
    }
}
