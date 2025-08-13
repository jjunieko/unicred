<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Domain\Shared\Exceptions\DomainException;
use Illuminate\Validation\ValidationException as LaravelValidationException;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // Eexeccoes de dominio 
        $this->renderable(function (DomainException $e, $request) {
            return response()->json([
                'error' => [
                    'type'    => $e->type(),
                    'message' => $e->getMessage(),
                    'details' => $e->details(),
                ]
            ], $e->status());
        });

        // valida 422
        $this->renderable(function (LaravelValidationException $e, $request) {
            return response()->json([
                'error' => [
                    'type'    => 'ValidationException',
                    'message' => 'Validation failed.',
                    'details' => $e->errors(),
                ]
            ], 422);
        });

        $this->renderable(function (\InvalidArgumentException $e, $request) {
            return response()->json([
                'error' => [
                    'type'    => 'InvalidArgumentException',
                    'message' => $e->getMessage(),
                    'details' => [],
                ]
            ], 422);
        });
    }
}
