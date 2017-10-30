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
            'cnpj' => 'required|max:18',
            'nome_fantasia' => 'required|max:255',
            'razao_social' => 'required|max:255',
            'nome_usuario' => 'required|max:255',
            'senha' => 'required|max:255',
            'email' => 'required|email|max:255',
        ];
    }
}
