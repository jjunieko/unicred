<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCooperadoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nome'   => ['sometimes','string','max:255'],
            'cpfCnpj'=> ['sometimes','string','max:18'],
            'data'   => ['sometimes','date'],
            'rendaFaturamento' => ['sometimes','numeric','min:0'],
            'telefone' => ['sometimes','string','max:25'],
            'email' => ['nullable','email','max:255'],
        ];
    }
}