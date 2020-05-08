<?php


namespace App\Domain\Core;

use App\Domain\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintValidator
{
    /**
     * @param ConstraintViolationListInterface $constraintList
     * @throws ValidatorException
     */
    public static function handleViolation(ConstraintViolationListInterface $constraintList): void
    {
        if (count($constraintList) > 0) {
            $errors = [];

            /** @var ConstraintViolationInterface $constraint */
            foreach ($constraintList as $constraint) {
                $errors[$constraint->getPropertyPath()][] = $constraint->getMessage();
            }

            throw new ValidatorException(
                $errors,
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}