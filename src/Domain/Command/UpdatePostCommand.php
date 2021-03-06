<?php


namespace App\Domain\Command;


class UpdatePostCommand extends AbstractCommand
{
    /** @var string|null $postId */
    private $postId;

    /** @var string|null $title */
    private $title;

    /** @var string|null $abstract */
    private $abstract;

    /** @var string|null $content */
    private $content;

    public function setPostId(string $postId): UpdatePostCommand
    {
        $this->postId = $postId;
        return $this;
    }

    public function getPostId(): ?string
    {
        return $this->postId;
    }

    public function setTitle(string $title): UpdatePostCommand
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setAbstract(string $abstract): UpdatePostCommand
    {
        $this->abstract = $abstract;
        return $this;
    }

    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    public function setContent(string $content): UpdatePostCommand
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}