<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CooperadoModel extends Model
{
    use SoftDeletes;

    protected $table = 'cooperados';

    public $incrementing = false;   // id é UUID vem com hash
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nome',
        'cpf_cnpj',
        'data_nascimento_constituicao',
        'renda_faturamento',
        'telefone',
        'email',
    ];

    protected $casts = [
        'data_nascimento_constituicao' => 'date:Y-m-d',
        'renda_faturamento' => 'decimal:2',
    ];
}
