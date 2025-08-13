<?php

namespace Tests\Unit\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use App\Domain\Cooperado\ValueObjects\CpfCnpj;
use App\Domain\Cooperado\ValueObjects\Telefone;
use App\Domain\Cooperado\ValueObjects\Email;

class ValueObjectsTest extends TestCase
{
    public function test_cpf_valido_e_invalido(): void
    {
        $ok = new CpfCnpj('111.444.777-35');
        $this->assertSame('11144477735', $ok->numero);

        $this->expectException(\InvalidArgumentException::class);
        new CpfCnpj('123.456.789-00'); // inválido
    }

    public function test_cnpj_valido_e_invalido(): void
    {
        $ok = new CpfCnpj('04.252.011/0001-10');
        $this->assertSame('04252011000110', $ok->numero);

        $this->expectException(\InvalidArgumentException::class);
        new CpfCnpj('00.000.000/0000-00');
    }

    public function test_telefone_normaliza_com_ddi_55_e_valida(): void
    {
        $telMovel = new Telefone('+55 (41) 98888-8888');
        $this->assertSame('5541988888888', $telMovel->numero);

        $telFixo = new Telefone('41 3344-1234'); // sem +55, fixa com 8 dígitos
        $this->assertSame('554133441234', $telFixo->numero);

        $this->expectException(\InvalidArgumentException::class);
        new Telefone('9999'); // inválido
    }

    public function test_email_valido_e_invalido(): void
    {
        $ok = new Email('Fulano+tag@Example.com');
        $this->assertSame('Fulano+tag@Example.com', $ok->email);

        $this->expectException(\InvalidArgumentException::class);
        new Email('coisa@-dominio-.com'); 
    }

}
