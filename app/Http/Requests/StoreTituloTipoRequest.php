<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTituloTipoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'valor' => 'max:255',
            'data_pagamento' => 'max:255',
            'identificador' => 'max:255',
            'data_emissao' => 'max:255',
            'situacao' => 'max:255',
        ];
    }
    
    public function required()
    {
        return [
            'valor' => 'required',
            'data_pagamento' => 'required',
            'identificador' => 'required',
            'data_emissao' => 'required',
            'situacao' => 'required',
        ];
    }
}
            