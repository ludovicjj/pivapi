<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTime;

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

    /** @var array<int, string> $roles */
    private $roles;

    /** @var DateTime $createdAt */
    private $createdAt;

    /** @var null|DateTime $updatedAt */
    private $updatedAt;

    /** @var ArrayCollection $posts */
    private $posts;

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
        $this->createdAt = new DateTime();
        $this->updatedAt = null;
        $this->posts = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setFirstname(string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setLastname(string $lastname): User
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setRoles(array $role): User
    {
        $this->roles = $role;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setCreatedAt(DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }


    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
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

    public function getPosts()
    {
        return $this->posts;
    }

    public function addPost(Post $post): User
    {
        $this->posts->add($post);
        return $this;
    }

    public function removePost(Post $post): User
    {
        $this->posts->removeElement($post);
        return $this;
    }
}