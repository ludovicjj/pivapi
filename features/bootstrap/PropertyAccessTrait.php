<?php

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Behat\Mink\Exception\ExpectationException;

trait PropertyAccessTrait
{
    private $propertyAccessor;

    /**
     * @param $object
     * @param $property
     * @param $value
     * @throws ExpectationException
     */
    private function testPropertyEqualsValue($object, $property, $value)
    {
        $class = substr(get_class($object), strrpos(get_class($object), '\\') + 1);

        [$value, $actual] = $this->getActualValues($object, $property, $value);

        if ($value != $actual) {
            throw new ExpectationException(
                sprintf(
                    "%s::get%s() should be equal to \"%s\" but have \"%s\"",
                    $class, ucfirst($property), $actual, $value),
                $this->getSession()->getDriver()
            );
        }
    }

    private function getActualValues($object, $property, $value)
    {
        $actual = $this->propertyAccessor->getValue($object, $property);
        return [$value, $actual];
    }
}