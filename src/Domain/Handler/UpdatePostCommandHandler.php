<?php


namespace App\Domain\Handler;


use App\Domain\Command\UpdatePostCommand;
use App\Domain\Entity\Post;
use App\Domain\Repository\PostRepository;

class UpdatePostCommandHandler
{
    /** @var PostRepository $postRepository */
    private $postRepository;

    public function __construct(
        PostRepository $postRepository
    ) {
       $this->postRepository = $postRepository;
    }

    /**
     * @param UpdatePostCommand $command
     * @throws \Exception
     */
    public function handle(UpdatePostCommand $command): void
    {
        /** @var Post $post */
        $post = $this->postRepository->find($command->getPostId());

        $post->update(
            $command->getTitle(),
            $command->getAbstract(),
            $command->getContent()
        );
    }
}