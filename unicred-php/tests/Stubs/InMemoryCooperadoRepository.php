<?php

namespace Tests\Stubs;

use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Repositories\CooperadoRepository;

class InMemoryCooperadoRepository implements CooperadoRepository
{
    /** @var array<string,Cooperado> */
    private array $byId = [];
    /** @var array<string,string> cpfCnpj => id   */ 
    private array $cpfIndex = [];
    /** @var array<string,bool> */
    private array $softDeleted = [];

    public function create(Cooperado $c): Cooperado
    {
        $this->byId[$c->id] = $c;
        $this->cpfIndex[$c->cpfCnpj->numero] = $c->id;
        return $c;
    }

    // public function update(Cooperado $c): Cooperado
    // {
    //     $old = $this->byId[$c->id] ?? null;
    //     if ($old && $old->cpfCnpj->numero !== $c->cpfCnpj->numero) {
    //         unset($this->cpfIndex[$old->cpfCnpj->numero]);
    //     }
    //     $this->byId[$c->id] = $c;
    //     $this->cpfIndex[$c->cpfCnpj->numero] = $c->id; 
    //     return $c;
    // }
    public function update(Cooperado $c): Cooperado
    {
        $oldKey = array_search($c->id, $this->cpfIndex, true);

        if ($oldKey !== false && $oldKey !== $c->cpfCnpj->numero) {
            unset($this->cpfIndex[$oldKey]);
        }

        $this->byId[$c->id] = $c;
        $this->cpfIndex[$c->cpfCnpj->numero] = $c->id;

        return $c;
    }


    public function findById(string $id): ?Cooperado
    {
        if (($this->softDeleted[$id] ?? false) === true) {
            return null;
        }
        return $this->byId[$id] ?? null;
    }

    public function findByCpfCnpj(string $cpfCnpj): ?Cooperado
    {
        $id = $this->cpfIndex[$cpfCnpj] ?? null;
        return $id ? ($this->byId[$id] ?? null) : null;
    }

    public function paginate(int $page=1, int $perPage=20, ?string $search=null): array
    {
        $items = array_filter($this->byId, fn($c, $id) =>
            !($this->softDeleted[$id] ?? false), ARRAY_FILTER_USE_BOTH);

        if ($search) {
            $s = mb_strtolower($search);
            $items = array_filter($items, fn(Cooperado $c) =>
                str_contains(mb_strtolower($c->nome), $s) ||
                str_contains($c->cpfCnpj->numero, $search)
            );
        }

        $items = array_values($items);
        $offset = ($page - 1) * $perPage;
        return array_slice($items, $offset, $perPage);
    }

    public function softDelete(string $id): void
    {
        if (isset($this->byId[$id])) {
            $this->softDeleted[$id] = true;
        }
    }
}
