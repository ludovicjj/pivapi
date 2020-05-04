<?php

namespace App\Domain\Exceptions;

use Throwable;

class JWTException extends \Exception
{
    /** @var string $error */
    private $error;

    /** @var int $statusCode */
    private $statusCode;

    public function __construct(
        string $error = "",
        int $statusCode = 401
    ) {
        $this->error = $error;
        $this->statusCode = $statusCode;
        parent::__construct();
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}