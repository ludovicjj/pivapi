<?php


namespace App\Action\Post;

use App\Domain\Core\LinkBuilder;
use App\Domain\Core\OrderTransformer;
use App\Domain\Core\OutputSearchResult;
use App\Domain\Core\ParameterBagTransformer;
use App\Domain\Exceptions\InvalidArgumentException;
use App\Domain\Exceptions\UnknownDirectionException;
use App\Domain\Repository\PostRepository;
use App\Domain\Request\SearchPostRequestHandler;
use App\Domain\Search\PostSearch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SearchPost
{
    /** @var PostRepository $postRepository */
    private $postRepository;

    /** @var ParameterBagTransformer $parameterBagTransformer */
    private $parameterBagTransformer;

    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var UrlGeneratorInterface $urlGenerator */
    private $urlGenerator;

    /** @var SearchPostRequestHandler $requestHandler */
    private $requestHandler;

    private $linkBuilder;

    public function __construct(
        PostRepository $postRepository,
        ParameterBagTransformer $parameterBagTransformer,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        SearchPostRequestHandler $requestHandler,
        LinkBuilder $linkBuilder
    ) {
        $this->postRepository = $postRepository;
        $this->parameterBagTransformer = $parameterBagTransformer;
        $this->serializer = $serializer;
        $this->urlGenerator = $urlGenerator;
        $this->requestHandler = $requestHandler;
        $this->linkBuilder = $linkBuilder;
    }

    /**
     * @Route("/api/posts", name="api_search_post", methods={"GET"})
     * @param Request $request
     * @throws UnknownDirectionException
     * @throws InvalidArgumentException
     * @return Response
     */
    public function search(Request $request)
    {
        $this->requestHandler->handle($request);

        $page = $request->query->get('page', 1);
        $items = $request->get('items', 5);

        $paginator = $this->postRepository->search(
            new PostSearch(
                $request->query->get('filters', []),
                OrderTransformer::transformToArray($request->query->get('order')),
                $page,
                $items
            )
        );

        $this->linkBuilder->build($items, $paginator->count(), $page);

        $output = new OutputSearchResult(
            iterator_to_array($paginator),
            $this->linkBuilder->getNbItems(),
            $this->linkBuilder->getNbPages(),
            $this->linkBuilder->getLinks()
        );

        $context = $this->parameterBagTransformer->transformQueryToContext($request->query);

        return new Response(
            $this->serializer->serialize(
                $output,
                'json',
                $context
            ),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}