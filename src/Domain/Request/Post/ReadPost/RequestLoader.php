<?php


namespace App\Domain\Request\Post\ReadPost;


use App\Domain\Entity\Post;
use App\Domain\Exceptions\PostNotFoundException;
use App\Domain\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;

class RequestLoader
{
    /** @var PostRepository $postRepository */
    private $postRepository;

    public function __construct(
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
    }

    /**
     * @param Request $request
     * @throws PostNotFoundException
     * @return Post
     */
    public function load(Request $request)
    {
        $post = $this->loadFromDatabase($request);

        if (is_null($post)) {
            throw new PostNotFoundException(
                sprintf('Not found post with id %s', $request->attributes->get('postId'))
            );
        }

        return $post;
    }

    /**
     * @param Request $request
     * @return Post|null
     */
    private function loadFromDatabase(Request $request): ?Post
    {
        return $this->postRepository->find($request->attributes->get('postId'));
    }
}