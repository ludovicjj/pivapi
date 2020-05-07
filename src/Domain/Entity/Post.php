<?php


namespace App\Domain\Entity;

use DateTime;

class Post
{
    /** @var string $id */
    private $id;

    /** @var string $title */
    private $title;

    /** @var string $abstract */
    private $abstract;

    /** @var string $content */
    private $content;

    /** @var DateTime $createdAt */
    private $createdAt;

    /** @var null|DateTime $updatedAt */
    private $updatedAt;

    /** @var User $user */
    private $user;

    public function __construct(
        string $id,
        string $title,
        string $abstract,
        string $content,
        User $user
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->abstract = $abstract;
        $this->content = $content;
        $this->createdAt = new DateTime();
        $this->updatedAt = null;
        $this->user = $user;
    }

    public function setId(string $id): Post
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setTitle(string $title): Post
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setAbstract($abstract): Post
    {
        $this->abstract = $abstract;
        return $this;
    }

    public function getAbstract(): string
    {
        return $this->abstract;
    }

    public function setContent(string $content): Post
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setCreatedAt(DateTime $dateTime): Post
    {
        $this->createdAt = $dateTime;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(DateTime $dateTime)
    {
        $this->updatedAt = $dateTime;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUser(User $user): Post
    {
        $this->user = $user;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}