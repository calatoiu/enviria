<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacturaRequest extends FormRequest
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
            'SerieNumar' => ['required', 'string'],
            'Data' => ['required','string'],
            'CUI' => ['required', 'integer' ],
            'Furnizor' => ['required','string'],
            'LunaIni' => ['nullable', 'string'],
            'LunaFin' => ['nullable', 'string'],
            'Valoare' => ['required','numeric'],
            'Continut' => ['nullable'],
            'Nota' => ['nullable','string'],
            'FaraContract' => ['required', 'integer'],
            'NotaText' => ['required', 'integer'],
        ];
    }
}
