<?php

namespace App\Domain\Cooperado\ValueObjects;

final class Telefone
{
    public string $numero; // sempre digits-only com DDI 55


    //DDD com 2 dígitos, não iniciando com 0.

    // Móvel: 9 dígitos iniciando com 9.

    //Fixo: 8 dígitos(ex.: +55 (41) 3344-1234).

    //Bloqueia sequências repetidas (ex.: 999999999).
    public function __construct(string $numero)
    {
        $n = preg_replace('/\D+/', '', $numero) ?? '';
        if ($n === '') {
            throw new \InvalidArgumentException('Telefone inválido');
        }

        // remove DDI 55 se vier; padronizo depois
        if (str_starts_with($n, '55')) {
            $n = substr($n, 2);
        }

        // precisa ser DDD(2) + local(8|9)
        if (!preg_match('/^\d{10,11}$/', $n)) {
            throw new \InvalidArgumentException('Telefone inválido');
        }

        $ddd   = substr($n, 0, 2);
        $local = substr($n, 2);

        if ($ddd[0] === '0') {
            throw new \InvalidArgumentException('Telefone inválido');
        }

        // bloqueia locais só com o mesmo dígito
        if (preg_match('/^(\d)\1+$/', $local)) {
            throw new \InvalidArgumentException('Telefone inválido');
        }

        // regras: móvel 9 dígitos (começa com 9), fixo 8 dígitos (agora 1–9)
        if (strlen($local) === 9) {
            if (!preg_match('/^9\d{8}$/', $local)) {
                throw new \InvalidArgumentException('Telefone inválido');
            }
        } elseif (strlen($local) === 8) {
            if (!preg_match('/^[1-9]\d{7}$/', $local)) {
                throw new \InvalidArgumentException('Telefone inválido');
            }
        } else {
            throw new \InvalidArgumentException('Telefone inválido');
        }

        // padroniza para DDI 55 + DDD + local (digits-only)
        $this->numero = '55' . $ddd . $local;
    }

    public function e164(): string
    {
        return '+' . $this->numero;
    }

    public function ddd(): string
    {
        return substr($this->numero, 2, 2);
    }
}