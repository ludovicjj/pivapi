<?php


namespace App\Domain\Exceptions;


use Throwable;

class PostNotFoundException extends \Exception
{
    /** @var int $statusCode */
    private $statusCode;

    public function __construct($message = "", $statusCode = 400, Throwable $previous = null)
    {
        $this->statusCode = $statusCode;

        parent::__construct($message, $statusCode, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}