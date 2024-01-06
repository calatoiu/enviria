<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutorizatieRequest extends FormRequest
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
            'AutorizatieID' => ['required', 'string'],
            'CIF' => ['required', 'integer' ],
            'PunctLucruID' => ['required', 'string'],
            'Nr' => ['required', 'string'],
            'Data' => ['required', 'string'],
            'Nota' => ['nullable', 'string'],
            'NrRevizuire' => ['nullable', 'string'],
            'DataRevizuire' => ['nullable', 'string'],
            'NrDecVal' => ['nullable', 'string'],
            'DataDecVal' => ['nullable', 'string'],
        ];
    }
}
