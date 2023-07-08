<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenggunaRequest extends FormRequest
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
            "name" => ["required", "string"],
            "username" => ["required", "string"],
            "role" => ["required", "string"],
            "password" => ["required", "string"],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'username' => str_replace(' ', '', strtolower($this->username)),
            'password' => bcrypt($this->password),
        ]);
    }
}
