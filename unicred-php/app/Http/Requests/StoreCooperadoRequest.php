<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCooperadoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nome'   => ['required','string','max:255'],
            'cpfCnpj'=> ['required','string','max:18'],
            'data'   => ['required','date'],
            'rendaFaturamento' => ['required','numeric','min:0'],
            'telefone' => ['required','string','max:25'],
            'email' => ['nullable','email','max:255'],
        ];
    }
}