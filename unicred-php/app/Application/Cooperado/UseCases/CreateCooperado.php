<?php

namespace App\Application\Cooperado\UseCases;

use App\Application\Cooperado\DTOs\CreateCooperadoDTO;
use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Repositories\CooperadoRepository;
use App\Domain\Cooperado\ValueObjects\{CpfCnpj, Telefone, Email};
use App\Domain\Shared\Exceptions\ConflictException; 
use Illuminate\Support\Str;

class CreateCooperado
{
    public function __construct(private CooperadoRepository $repo) {}

    public function handle(CreateCooperadoDTO $dto): Cooperado
    {
        $numero = preg_replace('/\D/', '', $dto->cpfCnpj);
        if ($this->repo->findByCpfCnpj($numero)) {
            throw new ConflictException('CPF/CNPJ já cadastrado.');
        }

        $entity = new Cooperado(
            Str::uuid()->toString(),
            trim($dto->nome),
            new CpfCnpj($dto->cpfCnpj),
            new \DateTimeImmutable($dto->data),
            (float)$dto->rendaFaturamento,
            new Telefone($dto->telefone),
            $dto->email ? new Email($dto->email) : null
        );

        return $this->repo->create($entity);
    }
}
