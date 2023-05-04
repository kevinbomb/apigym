<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstrukturRequest extends FormRequest
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
            'NAMA_INSTRUKTUR' => 'required',
            'TANGGAL_LAHIR_INSTRUKTUR' => 'required | date',
            'ALAMAT_INSTRUKTUR'=> 'required',
            'NO_TELP_INSTRUKTUR'=> 'required',
            'PASSWORD_INSTRUKTUR'=> 'required'
        ];
    }
}
