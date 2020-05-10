<?php


namespace App\Domain\Handler;


use App\Domain\Command\DeletePostCommand;
use App\Domain\Exceptions\PostNotFoundException;
use App\Domain\Repository\PostRepository;
use Doctrine\ORM\ORMException;

class DeletePostCommandHandler
{
    /** @var PostRepository $postRepository */
    private $postRepository;

    public function __construct(
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
    }

    /**
     * @param DeletePostCommand $command
     * @throws PostNotFoundException
     * @throws ORMException
     */
    public function handle(DeletePostCommand $command): void
    {
        $post = $this->postRepository->find($command->getPostId());

        if (is_null($post)) {
            throw new PostNotFoundException(
                sprintf('Post with id %s not found', $command->getPostId()),
                400
            );
        }

        $this->postRepository->remove($post);
    }
}