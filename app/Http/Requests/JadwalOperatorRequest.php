<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JadwalOperatorRequest extends FormRequest
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
            "is_beda_hari" => ["required", "boolean"],
            "user" => ["required", "string"],
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'user' => auth()->user()->name,
        ]);
    }
}
