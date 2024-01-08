<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TidakMasukRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "pegawai_id" => ["required", "exists:pegawais,id"],
            "tanggal_mulai" => ["date_format:Y-m-d", "string"],
            "tanggal_selesai" => ["date_format:Y-m-d", "string"],
            "jenis_izin_id" => ["nullable", "exists:jenis_izin,id"],
            "status" => ["required", "boolean"],
            "keterangan" => ["required", "string"],
            "file" => ["required", "mimes:jpg,png", "max:1024"],
            "user" => ["required", "string"],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tanggal_mulai' => date('Y-m-d', strtotime($this->tanggal_mulai)),
            'tanggal_selesai' => date('Y-m-d', strtotime($this->tanggal_selesai)),
            'status'=>0,
            'user' => Auth::user()->name,
        ]);
    }
}
