<?php

namespace App\Domain\Cooperado\Repositories;

use App\Domain\Cooperado\Entities\Cooperado;

interface CooperadoRepository
{
    public function create(Cooperado $c): Cooperado;
    public function update(Cooperado $c): Cooperado;
    public function findById(string $id): ?Cooperado;
    public function findByCpfCnpj(string $cpfCnpj): ?Cooperado;
    /** @return Cooperado[] */
    public function paginate(int $page=1, int $perPage=20, ?string $search=null): array;
    public function softDelete(string $id): void;
}