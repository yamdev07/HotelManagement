<?php

namespace App\Exceptions;

use RuntimeException;

abstract class HotelException extends RuntimeException
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function httpStatusCode(): int
    {
        return 422;
    }

    public function toArray(): array
    {
        return [
            'error'   => class_basename($this),
            'message' => $this->getMessage(),
            'code'    => $this->getCode(),
        ];
    }
}
