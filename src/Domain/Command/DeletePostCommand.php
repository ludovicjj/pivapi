<?php


namespace App\Domain\Command;


class DeletePostCommand
{
    /** @var string $postId */
    private $postId;

    public function __construct(string $postId)
    {
        $this->postId = $postId;
    }

    public function getPostId(): string
    {
        return $this->postId;
    }
}