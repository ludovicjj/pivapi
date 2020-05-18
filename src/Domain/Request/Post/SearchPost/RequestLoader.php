<?php


namespace App\Domain\Request\Post\SearchPost;


use App\Domain\Core\OrderTransformer;
use App\Domain\Exceptions\UnknownQueryException;
use App\Domain\Repository\PostRepository;
use App\Domain\Search\PostSearch;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use App\Domain\Exceptions\UnknownDirectionException;
use App\Domain\Exceptions\UnknownOrderException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Get post paginated
     *
     * @param Request $request
     * @return Paginator
     *
     * @throws UnknownQueryException
     * @throws UnknownDirectionException
     * @throws UnknownOrderException
     * @throws NotFoundHttpException
     */
    public function load(Request $request): Paginator
    {
        $this->initializedQueryParams($request);
        $this->validQueryParams($request);
        return $this->loadFromDatabase($request);
    }

    /**
     * Initialize query parameter "page" and "items".
     *
     * @param Request $request
     */
    private function initializedQueryParams(Request $request): void
    {
        if (!$request->query->has('page')) {
            $request->query->set('page', 1);
        }

        if (!$request->query->has('items')) {
            $request->query->set('items', 5);
        }
    }

    /**
     * Valid query parameters "page" and "items".
     * Important : Convert type only into condition to keep value.
     *
     * @param Request $request
     * @throws UnknownQueryException
     * @throws NotFoundHttpException
     */
    private function validQueryParams(Request $request): void
    {
        $page = $request->query->get('page');
        $items = $request->query->get('items');


        if ((int)$page < 1) {
            throw new NotFoundHttpException(
                sprintf('Not found page %s', $page)
            );
        }

        if ((int)$items < 1) {
            throw new UnknownQueryException(
                sprintf('Expected query parameter items must be int and greater than 0, %s given', $items),
                400
            );
        }
    }

    /**
     * @param Request $request
     * @return Paginator
     * @throws UnknownDirectionException
     * @throws UnknownOrderException
     */
    private function loadFromDatabase(Request $request): Paginator
    {
        return $this->postRepository->search(
            new PostSearch(
                $request->query->get('filters', []),
                OrderTransformer::transformToArray($request->query->get('order')),
                $request->query->get('page'),
                $request->query->get('items')
            )
        );
    }
}