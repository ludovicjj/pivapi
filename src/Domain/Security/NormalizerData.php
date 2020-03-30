<?php

namespace App\Domain\Security;

use App\Domain\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Exception;

class NormalizerData
{
    /** @var JWTManager $jwtManager */
    private $jwtManager;

    public function __construct(
        JWTManager $jwtManager
    ) {
        $this->jwtManager = $jwtManager;
    }

    /**
     * @param UserInterface $user
     * @return array<string, string|int>
     * @throws Exception
     */
    public function normalize(UserInterface $user): array
    {
        /** @var User $currentUser */
        $currentUser = $user;

        $exp = time() + 3600;
        $token = $this->jwtManager->createToken(
            $exp,
            [
                'id' => $currentUser->getId(),
                'email' => $currentUser->getEmail()
            ]
        );

        return [
            'token' => $token->__toString(),
            'user' => $user->getUsername(),
            'status' => Response::HTTP_OK,
        ];
    }
}