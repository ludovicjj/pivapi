<?php

namespace App\Domain\Security;

use App\Domain\Entity\User;
use App\Domain\Serializer\UserNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Exception;

class NormalizerData
{
    /** @var JWTManager $jwtManager */
    private $jwtManager;

    /** @var NormalizerInterface $normalizer */
    private $normalizer;

    public function __construct(
        JWTManager $jwtManager,
        NormalizerInterface $normalizer
    ) {
        $this->jwtManager = $jwtManager;
        $this->normalizer = $normalizer;
    }

    /**
     * @param UserInterface $user
     *
     * @return array
     *
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

        $userNormalized = $this->normalizer->normalize(
            $currentUser,
            'json',
            [
                'query' => [
                    'fields' => [
                        UserNormalizer::OBJECT_TYPE => [
                            'id',
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
            'user' => $userNormalized,
            'status' => Response::HTTP_OK,
        ];
    }
}