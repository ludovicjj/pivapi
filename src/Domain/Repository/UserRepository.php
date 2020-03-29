<?php

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends AbstractRepository implements UserLoaderInterface
{
    public function getEntityClassName(): string
    {
        return User::class;
    }

    /**
     * @param string $usernameOrEmail
     *
     * @return mixed|UserInterface|null
     * @throws NonUniqueResultException
     */
    public function loadUserByUsername($usernameOrEmail)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->orWhere('u.email = :username')
            ->setParameter('username', $usernameOrEmail)
            ->getQuery()
            ->getOneOrNullResult();
    }
}