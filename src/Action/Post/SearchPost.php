<?php


namespace App\Action\Post;


use App\Domain\Builder\PaginationBuilder;
use App\Domain\Core\OutputSearchResult;
use App\Domain\Core\ParameterBagTransformer;
use App\Domain\Exceptions\UnknownQueryException;
use App\Domain\Exceptions\UnknownDirectionException;
use App\Domain\Exceptions\UnknownOrderException;
use App\Domain\Request\Post\SearchPost\RequestLoader;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SearchPost
{
    /** @var RequestLoader $requestLoader */
    private $requestLoader;

    /** @var ParameterBagTransformer $parameterBagTransformer */
    private $parameterBagTransformer;

    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var PaginationBuilder $paginationBuilder */
    private $paginationBuilder;

    public function __construct(
        RequestLoader $requestLoader,
        PaginationBuilder $paginationBuilder,
        ParameterBagTransformer $parameterBagTransformer,
        SerializerInterface $serializer
    ) {
        $this->requestLoader = $requestLoader;
        $this->paginationBuilder = $paginationBuilder;
        $this->parameterBagTransformer = $parameterBagTransformer;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/posts", name="api_search_post", methods={"GET"})
     * @param Request $request
     * @return Response
     * @throws UnknownQueryException
     * @throws UnknownOrderException
     * @throws UnknownDirectionException
     */
    public function search(Request $request)
    {
        /** @var Paginator $paginatedPosts */
        $paginatedPosts = $this->requestLoader->load($request);

        $pagination = $this->paginationBuilder->build($paginatedPosts->count())->getPagination();

        $output = new OutputSearchResult(iterator_to_array($paginatedPosts), $pagination);
        $context = $this->parameterBagTransformer->transformQueryToContext($request->query);

        return new Response(
            $this->serializer->serialize($output, 'json', $context),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}