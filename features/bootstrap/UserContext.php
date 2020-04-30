<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use App\Domain\Repository\UserRepository;
use Behat\Mink\Exception\ExpectationException;

class UserContext extends RawMinkContext implements Context
{
    /** @var UserRepository $userRepository */
    private $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @Then user with email :email should exist in database
     * @param $email
     *
     * @throws ExpectationException
     */
    public function userWithEmailShouldExistInDatabase($email)
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (is_null($user)) {
            throw new ExpectationException(
                sprintf('User with email %s should be exist', $email),
                $this->getSession()->getDriver()
            );
        }
    }
}