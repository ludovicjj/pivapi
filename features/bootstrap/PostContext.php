<?php

use Behat\Behat\Context\Context;
use App\Domain\Repository\PostRepository;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\DriverException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class PostContext extends RawMinkContext implements Context
{
    /** @var PostRepository $postRepository */
    private $postRepository;

    /** @var PropertyAccessor $propertyAccessor */
    private $propertyAccessor;

    public function __construct(
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @Then the response should contain the id of a post existing in database
     *
     * @throws ExpectationException
     * @throws DriverException
     * @throws UnsupportedDriverActionException
     */
    public function theResponseShouldContainTheIdOfAPostExistingInDatabase()
    {
        $postId = json_decode($this->getSession()->getDriver()->getContent(), true)['data']['id'];

        $post = $this->postRepository->find($postId);
        if (is_null($post)) {
            throw new ExpectationException(
                sprintf("Expected post with id %s", $postId),
                $this->getSession()->getDriver()
            );
        }
    }

    /**
     * @Then post with id into the response should have :property equal to :value
     * @param $property
     * @param $value
     *
     * @throws DriverException
     * @throws UnsupportedDriverActionException
     * @throws ExpectationException
     */
    public function postWithIdIntoTheResponseShouldHaveEqualTo($property, $value)
    {
        $postId = json_decode($this->getSession()->getDriver()->getContent(), true)['data']['id'];
        $this->testPropertyEqualsValue($this->postRepository->find($postId), $property, $value);
    }

    /**
     * @param $object
     * @param $property
     * @param $value
     * @throws ExpectationException
     */
    private function testPropertyEqualsValue($object, $property, $value)
    {
        $class = substr(get_class($object), strrpos(get_class($object), '\\') + 1);

        [$value, $actual] = $this->getTestAndActualValues($object, $property, $value);

        if ($value != $actual) {
            throw new ExpectationException(
                sprintf(
                    "%s::get%s() should be equal to \"%s\" but get \"%s\"",
                    $class, ucfirst($property), $actual, $value),
                $this->getSession()->getDriver()
            );
        }
    }

    private function getTestAndActualValues($object, $property, $value)
    {
        $actual = $this->propertyAccessor->getValue($object, $property);
        return [$value, $actual];
    }
}