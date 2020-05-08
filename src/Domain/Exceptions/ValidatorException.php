<?php


namespace App\Domain\Exceptions;


use Throwable;

class ValidatorException extends \Exception
{
    /** @var array $errors */
    private $errors;

    /** @var int $statusCode */
    private $statusCode;

    public function __construct(
        array $errors,
        int $statusCode
    ) {
        $this->errors = $errors;
        $this->statusCode = $statusCode;
        parent::__construct();
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}