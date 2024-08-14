<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class JadwalShiftRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return true;
    }

   
    public function rules(): array
    {
        return [
            "nama" => ["required", "string"],
            "jam_masuk" => ["date_format:H:i", "string"],
            "jam_pulang" => ["date_format:H:i", "string"],
            "jam_keluar_istirahat" => ["date_format:H:i", "string"],
            "jam_masuk_istirahat" => ["date_format:H:i", "string"],
            "is_beda_hari" => ["required", "boolean"],
            "is_istirahat" => ["required", "boolean"],
            "user" => ["required", "string"],
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'user' => Auth::user()->name,
        ]);
    }
}
