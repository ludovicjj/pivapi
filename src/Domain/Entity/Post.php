<?php


namespace App\Domain\Entity;


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

    /** @var \DateTime $createdAt */
    private $createdAt;

    /** @var null|\DateTime $updatedAt */
    private $updatedAt;

    /**
     * Post constructor.
     * @param string $id
     * @param string $title
     * @param string $abstract
     * @param string $content
     *
     * @throws \Exception
     */
    public function __construct(
        string $id,
        string $title,
        string $abstract,
        string $content
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->abstract = $abstract;
        $this->content = $content;
        $this->createdAt = new \DateTime();
        $this->updatedAt = null;
    }

    /**
     * @param string $id
     * @return Post
     */
    public function setId(string $id): Post
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $title
     * @return Post
     */
    public function setTitle(string $title): Post
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param $abstract
     * @return Post
     */
    public function setAbstract($abstract): Post
    {
        $this->abstract = $abstract;
        return $this;
    }

    /**
     * @return string
     */
    public function getAbstract(): string
    {
        return $this->abstract;
    }

    /**
     * @param string $content
     * @return Post
     */
    public function setContent(string $content): Post
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}