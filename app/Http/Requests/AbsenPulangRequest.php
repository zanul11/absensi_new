<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AbsenPulangRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            "pegawai_id" => ["required", "exists:pegawais,id"],
            "tanggal" => ["date_format:Y-m-d H:i", "string"],
            "keterangan" => ["required", "string"],
            "file" => ["required", "mimes:jpg,png", "max:1024"],
            "user" => ["required", "string"],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tanggal' => date('Y-m-d H:i', strtotime($this->tanggal . ' ' . $this->jam)),
            'user' => Auth::user()->name,
        ]);
    }
}
