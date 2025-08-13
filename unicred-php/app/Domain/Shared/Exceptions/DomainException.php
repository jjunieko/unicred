<?php

namespace App\Domain\Shared\Exceptions;

abstract class DomainException extends \RuntimeException
{
    public function __construct(
        string $message = '',
        protected array $details = [],
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    abstract public function status(): int;

    public function details(): array
    {
        return $this->details;
    }

    public function type(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}
