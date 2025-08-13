<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Repositories\CooperadoRepository;
use App\Domain\Cooperado\ValueObjects\{CpfCnpj,Telefone,Email};
use App\Infrastructure\Persistence\Eloquent\Models\CooperadoModel;
use Illuminate\Support\Str;

class CooperadoRepositoryEloquent implements CooperadoRepository
{
    private function toModel(Cooperado $e): CooperadoModel
    {
        $m = CooperadoModel::find($e->id) ?? new CooperadoModel();
        $m->id = $e->id ?: Str::uuid()->toString();
        $m->nome = $e->nome;
        $m->cpf_cnpj = $e->cpfCnpj->numero;
        $m->data_nascimento_constituicao = $e->dataNascimentoConstituicao->format('Y-m-d');
        $m->renda_faturamento = $e->rendaFaturamento;
        $m->telefone = $e->telefone->numero;
        $m->email = $e->email?->email;
        return $m;
    }

    private function toEntity(CooperadoModel $m): Cooperado
    {
        return new Cooperado(
            $m->id,
            $m->nome,
            new CpfCnpj($m->cpf_cnpj),
            new \DateTimeImmutable($m->data_nascimento_constituicao),
            (float)$m->renda_faturamento,
            new Telefone($m->telefone),
            $m->email ? new Email($m->email) : null
        );
    }

    public function create(Cooperado $c): Cooperado
    {
        $model = $this->toModel($c);
        $model->save();
        return $this->toEntity($model);
    }

    public function update(Cooperado $c): Cooperado
    {
        $model = $this->toModel($c);
        $model->exists = true; // garante update
        $model->save();
        return $this->toEntity($model);
    }

    public function findById(string $id): ?Cooperado
    {
        $m = CooperadoModel::find($id);
        return $m ? $this->toEntity($m) : null;
    }

    public function findByCpfCnpj(string $cpfCnpj): ?Cooperado
    {
        $m = CooperadoModel::withTrashed()->where('cpf_cnpj', $cpfCnpj)->first();
        return $m ? $this->toEntity($m) : null;
    }

    public function paginate(int $page=1, int $perPage=20, ?string $search=null): array
    {
        $q = CooperadoModel::query();
        if ($search) {
            $q->where('nome', 'like', "%{$search}%")
              ->orWhere('cpf_cnpj', 'like', "%{$search}%");
        }
        return $q->orderBy('nome')
                 ->forPage($page, $perPage)
                 ->get()
                 ->map(fn($m) => $this->toEntity($m))
                 ->all();
    }

    public function softDelete(string $id): void
    {
        CooperadoModel::where('id', $id)->delete();
    }
}
