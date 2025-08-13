<?php

namespace App\Domain\Shared\Exceptions;

class NotFoundException extends DomainException
{
    public function status(): int { return 404; }
}
