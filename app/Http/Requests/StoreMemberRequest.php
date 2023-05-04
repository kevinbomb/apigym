<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
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
            'NO_MEMBER' => 'String',
            'NAMA_MEMBER' => 'required',
            'ALAMAT_MEMBER' => 'required',
            'TANGGAL_LAHIR_MEMBER' => 'required | date',
            'NO_TELP_MEMBER' => 'required',
        ];
    }
}
