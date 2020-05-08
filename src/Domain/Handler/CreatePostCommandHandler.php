<?php


namespace App\Domain\Handler;

use App\Domain\Command\CreatePostCommand;
use App\Domain\Entity\Post;
use App\Domain\Entity\User;
use App\Domain\Repository\PostRepository;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\ORMException;

class CreatePostCommandHandler
{
    /** @var Security $security */
    private $security;

    /** @var PostRepository $postRepository */
    private $postRepository;

    public function __construct(
        Security $security,
        PostRepository $postRepository
    ) {
        $this->security = $security;
        $this->postRepository = $postRepository;
    }

    /**
     * @param CreatePostCommand $command
     * @throws ORMException
     */
    public function handle(CreatePostCommand $command): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $post = new Post(
            $command->getPostId(),
            $command->getTitle(),
            $command->getAbstract(),
            $command->getContent(),
            $user
        );

        $this->postRepository->persist($post);
    }
}