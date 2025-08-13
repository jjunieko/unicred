<?php

namespace App\Application\Cooperado\UseCases;

use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Repositories\CooperadoRepository;


/**
 * Use Case para listar cooperados com paginação e busca opcional.
 */
class ListCooperados
{
    public function __construct(private CooperadoRepository $repo) {}

    /**
     * @return Cooperado[]
     */
    public function handle(int $page = 1, int $perPage = 20, ?string $search = null): array
    {
        return $this->repo->paginate($page, $perPage, $search);
    }
}
