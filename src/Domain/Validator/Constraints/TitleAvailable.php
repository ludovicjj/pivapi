<?php


namespace App\Domain\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class TitleAvailable extends Constraint
{
    public $message = "Il existe déja un article avec ce titre";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}