<?php

namespace App\Domain\Cooperado\Entities;

use App\Domain\Cooperado\ValueObjects\CpfCnpj;
use App\Domain\Cooperado\ValueObjects\Telefone;
use App\Domain\Cooperado\ValueObjects\Email;

class Cooperado
{
    public function __construct(
        public string $id,
        public string $nome,
        public CpfCnpj $cpfCnpj,
        public \DateTimeImmutable $dataNascimentoConstituicao,
        public float $rendaFaturamento,
        public Telefone $telefone,
        public ?Email $email = null,
    ) {}
}