<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Domain\Cooperado\Entities\Cooperado;
use App\Domain\Cooperado\ValueObjects\{CpfCnpj, Telefone, Email};

class CooperadoEntityTest extends TestCase
{
    public function test_cria_entidade_com_vos_normalizados(): void
    {
        $ent = new Cooperado(
            id: 'uuid-123',
            nome: 'Fulano de Tal',
            cpfCnpj: new CpfCnpj('111.444.777-35'),
            dataNascimentoConstituicao: new \DateTimeImmutable('1990-01-02'),
            rendaFaturamento: 12000.50,
            telefone: new Telefone('+55 (41) 98888-8888'),
            email: new Email('FULANO@EXAMPLE.COM')
        );

        $this->assertSame('uuid-123', $ent->id);
        $this->assertSame('Fulano de Tal', $ent->nome);
        $this->assertSame('11144477735', $ent->cpfCnpj->numero);
        $this->assertSame('1990-01-02', $ent->dataNascimentoConstituicao->format('Y-m-d'));
        $this->assertSame(12000.50, $ent->rendaFaturamento);
        $this->assertSame('5541988888888', $ent->telefone->numero);
        $this->assertSame('FULANO@EXAMPLE.COM', $ent->email->email);
    }
}
