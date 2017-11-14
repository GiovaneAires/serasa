<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTokenPut extends FormRequest
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
            'nome_usuario' => 'max:255',
            'senha' => 'max:255',
        ];
    }
    
    public function required()
    {
        return [
            'nome_usuario' => 'required',
            'senha' => 'required',
        ];
    }
}
