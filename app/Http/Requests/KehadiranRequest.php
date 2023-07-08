<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class KehadiranRequest extends FormRequest
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
            "pegawai_id" => ["required", "exists:pegawais,id"],
            "tanggal" => ["date_format:Y-m-d", "string"],
            "jenis_izin_id" => ["nullable", "exists:jenis_izin,id"],
            "jenis" => ["required", "boolean"],
            "jam" => ["date_format:H:i", "string"],
            "keterangan" => ["required", "string"],
            "user" => ["required", "string"],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tanggal' => date('Y-m-d', strtotime($this->tanggal)),
            'jam' => date('H:i', strtotime($this->jam)),
            'user' => Auth::user()->name,
        ]);
    }
}
