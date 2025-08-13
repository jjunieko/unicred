<?php

namespace App\Application\Cooperado\DTOs;

class CreateCooperadoDTO {
    public function __construct(
        public string $nome,
        public string $cpfCnpj,
        public string $data, // 'Y-m-d'
        public float $rendaFaturamento,
        public string $telefone,
        public ?string $email = null
    ) {}
}