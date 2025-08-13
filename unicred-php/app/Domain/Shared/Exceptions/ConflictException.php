<?php

namespace App\Domain\Shared\Exceptions;

class ConflictException extends DomainException
{
    public function status(): int { return 409; }
}
