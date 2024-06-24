<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PegawaiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

   
    public function rules(): array
    {
        return [
            "username" => ["required", "string"],
            "nip" => ["required", "string"],
            "name" => ["required", "string"],
            "alamat" => ["required", "string"],
            "nohp" => ["required", "string", "min:8"],
            "location_id" => ["required", "exists:locations,id"],
            "user" => ["required", "string"],
            "is_shift" => ["required", "boolean"],
            "is_operator" => ["required", "boolean"],
            "status_pegawai" => ["required", "boolean"],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'username' => str_replace(' ', '', strtolower($this->username)),
            'user' => Auth::user()->name,
        ]);
    }
}
