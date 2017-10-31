<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParceiroRequest extends FormRequest
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
            'cnpj' => 'max:18',
            'nome_fantasia' => 'max:255',
            'razao_social' => 'max:255',
            'nome_usuario' => 'max:255',
            'senha' => 'max:255',
            'email' => 'email|max:255',
        ];
    }
    
    public function required()
    {
        return [
            'nome_fantasia' => 'required',
            'razao_social' => 'required',
            'nome_usuario' => 'required',
            'senha' => 'required',
            'email' => 'required',
        ];
    }
}
