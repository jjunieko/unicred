<?php

namespace App\Domain\Cooperado\ValueObjects;

class Email
{
    public function __construct(public string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email inválido');
        }
    }
}
