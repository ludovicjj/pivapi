<?php


namespace App\Domain\Command;


class CreatePostCommand
{
    /** @var string|null $postId */
    private $postId;

    /** @var string|null $title */
    private $title;

    /** @var string|null $abstract */
    private $abstract;

    /** @var string|null $content */
    private $content;

    public function setPostId(string $postId): CreatePostCommand
    {
        $this->postId = $postId;
        return $this;
    }

    public function getPostId(): ?string
    {
        return $this->postId;
    }

    public function setTitle(string $title): CreatePostCommand
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setAbstract(string $abstract): CreatePostCommand
    {
        $this->abstract = $abstract;
        return $this;
    }

    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    public function setContent(string $content): CreatePostCommand
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}