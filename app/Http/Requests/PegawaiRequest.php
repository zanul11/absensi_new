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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "username" => ["required", "string"],
            "nip" => ["required", "string"],
            "name" => ["required", "string"],
            "password" => ["required", "string"],
            "alamat" => ["required", "string"],
            "nohp" => ["required", "string", "min:8"],
            "location_id" => ["required", "exists:locations,id"],
            "user" => ["required", "string"],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user' => Auth::user()->name,
            'password' => bcrypt($this->password),
        ]);
    }
}
