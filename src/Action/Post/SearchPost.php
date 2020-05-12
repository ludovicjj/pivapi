<?php


namespace App\Action\Post;

use App\Domain\Core\OrderTransformer;
use App\Domain\Core\OutputSearchResult;
use App\Domain\Core\ParameterBagTransformer;
use App\Domain\Exceptions\UnknownDirectionException;
use App\Domain\Repository\PostRepository;
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

    public function __construct(
        PostRepository $postRepository,
        ParameterBagTransformer $parameterBagTransformer,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->postRepository = $postRepository;
        $this->parameterBagTransformer = $parameterBagTransformer;
        $this->serializer = $serializer;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/api/posts", name="api_search_post", methods={"GET"})
     * @param Request $request
     * @throws UnknownDirectionException
     * @return Response
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

        $output = new OutputSearchResult(
            iterator_to_array($paginator),
            $request->get('items', 5),
            $paginator->count(),
            $request->query->get('page', 1),
            $request,
            $this->urlGenerator
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