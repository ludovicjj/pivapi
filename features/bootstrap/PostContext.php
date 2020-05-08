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
    use PropertyAccessTrait;

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
     * @Then post with id :postId should have :property equal to :value
     * @param $postId
     * @param $property
     * @param $value
     * @throws ExpectationException
     */
    public function postWithIdShouldHaveEqualTo($postId, $property, $value)
    {
        $this->testPropertyEqualsValue($this->postRepository->find($postId), $property, $value);
    }
}