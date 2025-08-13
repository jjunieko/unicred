<?php

namespace App\Domain\Cooperado\ValueObjects;

final class CpfCnpj
{
    public string $numero;

    public function __construct(string $numero)
    {
        $nums = self::sanitize($numero);
        if (!self::isValid($nums)) {
            throw new \InvalidArgumentException('CPF/CNPJ inválido');
        }
        $this->numero = $nums;
    }

    public static function sanitize(string $v): string
    {
        return preg_replace('/\D+/', '', $v) ?? '';
    }

    public static function isValid(string $n): bool
    {
        $len = strlen($n);
        if ($len === 11) return self::validaCpf($n);
        if ($len === 14) return self::validaCnpj($n);
        return false;
    }

    private static function validaCpf(string $cpf): bool
    {
        if (strlen($cpf) !== 11) return false;
        if (preg_match('/^(\d)\1{10}$/', $cpf)) return false;

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += (int)$cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ((int)$cpf[$t] !== $d) return false;
        }
        return true;
    }

    private static function validaCnpj(string $cnpj): bool
    {
        if (strlen($cnpj) !== 14) return false;
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) return false;

        $w1 = [5,4,3,2,9,8,7,6,5,4,3,2];
        $w2 = [6,5,4,3,2,9,8,7,6,5,4,3,2];

        $sum = 0;
        for ($i=0; $i<12; $i++) $sum += (int)$cnpj[$i] * $w1[$i];
        $d1 = $sum % 11;
        $d1 = ($d1 < 2) ? 0 : 11 - $d1;
        if ((int)$cnpj[12] !== $d1) return false;

        $sum = 0;
        for ($i=0; $i<13; $i++) $sum += (int)$cnpj[$i] * $w2[$i];
        $d2 = $sum % 11;
        $d2 = ($d2 < 2) ? 0 : 11 - $d2;
        return (int)$cnpj[13] === $d2;
    }
}
