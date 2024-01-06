<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PunctlucruRequest extends FormRequest
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
            'PunctLucruID' => ['required', 'string'],
            'Pass' => ['nullable','string'],
            'CIF' => ['required', 'integer' ],
            'Denumire' => ['nullable', 'string'],
            'DateContact' => ['nullable', 'string'],
            'Localitate' => ['nullable', 'string'],
            'Adresa' => ['nullable', 'string'],
            'Telefon' => ['nullable', 'string'],
            'Email' => ['nullable', 'string'],
            'CAEN' => ['nullable', 'string'],
            'NrSalariati' => ['required', 'integer' ],
        ];
    }
}
