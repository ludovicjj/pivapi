<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use App\Domain\Repository\UserRepository;
use App\Domain\Security\JWTManager;
use Behatch\HttpCall\Request;

class LoginContext extends RawMinkContext implements Context
{
    /** @var UserRepository $userRepository */
    private $userRepository;

    /** @var JWTManager $JWTManager */
    private $JWTManager;

    /** @var Request $request */
    private $request;

    public function __construct(
        UserRepository $userRepository,
        JWTManager $JWTManager,
        Request $request
    ) {
        $this->userRepository = $userRepository;
        $this->JWTManager = $JWTManager;
        $this->request = $request;
    }


    /**
     * @When I am connected with email :email
     * @throws Exception
     */
    public function iAmConnectedWithEmail($email)
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        $this->request->setHttpHeader(
            'Authorization',
            'Bearer '.$this->JWTManager->createToken(
                time() + 30,
                ['id' => $user->getId(), 'email' => $user->getEmail()]
            )
        );
    }

    /**
     * @When I am connected with email :email and expired token
     * @throws Exception
     */
    public function iAmConnectedWithEmailAndExpiredToken($email)
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        $this->request->setHttpHeader(
            'Authorization',
            'Bearer '.$this->JWTManager->createToken(
                time() ,
                ['id' => $user->getId(), 'email' => $user->getEmail()]
            )
        );
    }
}