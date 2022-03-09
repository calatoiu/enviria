<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'CIF' => ['required', 'integer' ],
            'Denumire' => ['required', 'string'],
            'ContBancar' => ['nullable','string'],
            'Banca' => ['nullable','string'],
            'Sediu' => ['nullable','string'],
            'Judet' => ['nullable','string'],
            'NrRegCom' => ['nullable','string'],
            'RO' => ['nullable','string'],
            'NrContract' => ['nullable','string'],
            'DataContract' => ['nullable','string'],
            'Valoare' => ['nullable','numeric'],
            'Furnizor' => ['nullable','string'],
            'Note' => ['nullable','string'],
        ];
    }
}
