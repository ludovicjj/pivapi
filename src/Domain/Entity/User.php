<?php

namespace App\Domain\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /** @var string $id */
    private $id;

    /** @var string $username */
    private $username;

    /** @var string $password */
    private $password;

    /** @var string $firstname */
    private $firstname;

    /** @var string $lastname */
    private $lastname;

    /** @var string $email */
    private $email;

    /** @var array $roles */
    private $roles;

    /** @var \DateTime $createdAt */
    private $createdAt;

    /** @var null $updatedAt */
    private $updatedAt;

    public function __construct(
        string $id,
        string $username,
        string $password,
        string $firstname,
        string $lastname,
        string $email
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->roles = [];
        $this->createdAt = new \DateTime();
        $this->updatedAt = null;
    }

    public function setRoles(string $role): void
    {
        $this->roles = $role;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return null|\DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return null
     */
    public function eraseCredentials()
    {
        return null;
    }
}