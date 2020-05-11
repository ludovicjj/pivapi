<?php


namespace App\Action\Post;

use App\Domain\Core\OrderTransformer;
use App\Domain\Exceptions\UnknownDirectionException;
use App\Domain\Repository\PostRepository;
use App\Domain\Search\PostSearch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchPost
{
    private $postRepository;

    public function __construct(
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/api/posts", name="api_search_post", methods={"GET"})
     * @param Request $request
     * @throws UnknownDirectionException
     */
    public function search(Request $request)
    {
        $paginator = $this->postRepository->search(
            new PostSearch(
                $request->query->get('filters', []),
                OrderTransformer::transformToArray($request->query->get('order')),
                $request->query->get('page', 1),
                $request->get('items', 5)
            )
        );
    }
}