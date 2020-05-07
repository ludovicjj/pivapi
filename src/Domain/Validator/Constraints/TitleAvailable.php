<?php


namespace App\Domain\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class TitleAvailable extends Constraint
{
    /** @var string $message */
    public $message = "Il existe déja un article avec ce titre";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}