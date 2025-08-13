<?php

namespace App\Application\Cooperado\UseCases;

use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\Repositories\CooperadoRepository;
use App\Domain\Cooperado\ValueObjects\{CpfCnpj, Telefone, Email};
use App\Domain\Shared\Exceptions\{NotFoundException, ConflictException};



/**
 * Use Case para atualizar um cooperado existente.
 */
class UpdateCooperado
{
    public function __construct(private CooperadoRepository $repo) {}

    /**
     * @param array{
     *   nome?: string,
     *   cpfCnpj?: string,
     *   data?: string,
     *   rendaFaturamento?: float|int|string,
     *   telefone?: string,
     *   email?: ?string
     * } $data
     */
    public function handle(string $id, array $data): Cooperado
    {
        $entity = $this->repo->findById($id);
        if (!$entity) {
            throw new NotFoundException('Cooperado não encontrado.');
        }

        // nome-
        if (array_key_exists('nome', $data) && $data['nome'] !== null) {
            $entity->nome = trim((string)$data['nome']);
        }

        // cpf/cnpj (com checagem de unicidade)
        if (array_key_exists('cpfCnpj', $data) && $data['cpfCnpj'] !== null) {
            $novoCpfCnpj = new CpfCnpj((string)$data['cpfCnpj']);
            $existente = $this->repo->findByCpfCnpj($novoCpfCnpj->numero);
            if ($existente && $existente->id !== $entity->id) {
                throw new ConflictException('CPF/CNPJ já cadastrado.');
            }
            $entity->cpfCnpj = $novoCpfCnpj;
        }

        // data nascimento/constituição
        if (array_key_exists('data', $data) && $data['data'] !== null) {
            $entity->dataNascimentoConstituicao = new \DateTimeImmutable((string)$data['data']);
        }

        // renda/faturamento
        if (array_key_exists('rendaFaturamento', $data) && $data['rendaFaturamento'] !== null) {
            $entity->rendaFaturamento = (float)$data['rendaFaturamento'];
        }

        // telefone
        if (array_key_exists('telefone', $data) && $data['telefone'] !== null) {
            $entity->telefone = new Telefone((string)$data['telefone']);
        }

        // email
        if (array_key_exists('email', $data)) {
            $email = $data['email'];
            $entity->email = ($email === null || $email === '')
                ? null
                : new Email((string)$email);
        }

        return $this->repo->update($entity);
    }
}
