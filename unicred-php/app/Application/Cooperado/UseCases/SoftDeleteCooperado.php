<?php

namespace App\Application\Cooperado\UseCases;

use App\Domain\Cooperado\Repositories\CooperadoRepository;



/**
 * Use Case para realizar soft delete de um cooperado.
 * 
 * Este caso de uso permite marcar um cooperado como excluído sem removê-lo fisicamente do banco de dados.
 */
class SoftDeleteCooperado
{
    public function __construct(private CooperadoRepository $repo) {}

    public function handle(string $id): void
    {
        if (!$this->repo->findById($id)) { return; }
        $this->repo->softDelete($id);
    }
}
