<?php

namespace Tests\Unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use Tests\Stubs\InMemoryCooperadoRepository;
use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\ValueObjects\{CpfCnpj, Telefone, Email};

class CooperadoRepositoryTest extends TestCase
{
    private function make(string $id, string $nome, string $doc): Cooperado
    {
        return new Cooperado(
            id: $id,
            nome: $nome,
            cpfCnpj: new CpfCnpj($doc),
            dataNascimentoConstituicao: new \DateTimeImmutable('1990-01-01'),
            rendaFaturamento: 1000.0,
            telefone: new Telefone('41 3344-1234'),
            email: new Email('a@a.com'),
        );
    }

    public function test_create_find_and_uniqueness_with_soft_delete(): void
    {
        $repo = new InMemoryCooperadoRepository();

        $c1 = $this->make('id1', 'Fulano', '111.444.777-35');
        $repo->create($c1);

        // find by id & cpf
        $this->assertNotNull($repo->findById('id1'));
        $this->assertNotNull($repo->findByCpfCnpj('11144477735'));

        // soft delete mantém unicidade
        $repo->softDelete('id1');
        $this->assertNull($repo->findById('id1')); 
        $this->assertNotNull($repo->findByCpfCnpj('11144477735')); 

        // tentar criar outro com mesmo CPF deve ser bloqueado no use case,
        // aqui só provamos que o repo ainda aponta para o antigo
        $this->assertSame('id1', (function() use ($repo) {
            $found = $repo->findByCpfCnpj('11144477735');
            return $found?->id;
        })());
    }

    public function test_update_troca_cpf_e_mantem_indices(): void
    {
        $repo = new InMemoryCooperadoRepository();

        $c1 = $this->make('id1', 'Fulano', '111.444.777-35');
        $repo->create($c1);

        // altera para um CNPJ válido...
        $c1->cpfCnpj = new CpfCnpj('04.252.011/0001-10');
        $repo->update($c1);

        $this->assertNotNull($repo->findByCpfCnpj('04252011000110'));
        $this->assertNull($repo->findByCpfCnpj('11144477735'));
    }
}
