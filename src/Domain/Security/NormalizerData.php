<?php

namespace App\Domain\Security;

use App\Domain\Entity\User;
use App\Domain\Serializer\UserNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class NormalizerData
{
    /** @var JWTManager $jwtManager */
    private $jwtManager;

    /** @var UserNormalizer */
    private $userNormalizer;

    private $normalizer;

    public function __construct(
        JWTManager $jwtManager,
        UserNormalizer $userNormalizer,
        NormalizerInterface $normalizer
    ) {
        $this->jwtManager = $jwtManager;
        $this->userNormalizer = $userNormalizer;
        $this->normalizer = $normalizer;
    }

    /**
     * @param UserInterface $user
     * @return array
     * @throws Exception
     * @throws ExceptionInterface
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

        $userNormalizer = $this->userNormalizer->normalize(
            $currentUser,
            'json',
            [
                'query' => [
                    'fields' => [
                        UserNormalizer::OBJECT_TYPE => [
                            'firstname',
                            'lastname',
                            'email',
                            'roles'
                        ]
                    ]
                ]
            ]
        );

        return [
            'token' => $token->__toString(),
            'user' => $userNormalizer,
            'status' => Response::HTTP_OK,
        ];
    }
}