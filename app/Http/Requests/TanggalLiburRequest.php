<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TanggalLiburRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "keterangan" => ["required", "string"],
            "tgl_libur" => ["required", 'string'],
            "user" => ["required", "string"],
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'tgl_libur' => date('Y-m-d', strtotime($this->tgl_libur)),
            'user' => Auth::user()->name,
        ]);
    }
}
