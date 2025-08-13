<?php

namespace App\Application\Cooperado\UseCases;

use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Repositories\CooperadoRepository;


/**
 * Use Case para obter um cooperado por ID.
 */
class GetCooperado
{
    public function __construct(private CooperadoRepository $repo) {}

    public function handle(string $id): ?Cooperado
    {
        return $this->repo->findById($id);
    }
}
