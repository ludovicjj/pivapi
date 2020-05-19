<?php


namespace App\Action\Post;


use App\Domain\Core\ParameterBagTransformer;
use App\Domain\Exceptions\PostNotFoundException;
use App\Domain\Exceptions\UnknownQueryException;
use App\Domain\Request\Post\ReadPost\RequestLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReadPost
{
    /** @var RequestLoader $requestLoader */
    private $requestLoader;

    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var ParameterBagTransformer $parameterBadTransformer */
    private $parameterBadTransformer;

    public function __construct(
        RequestLoader $requestLoader,
        SerializerInterface $serializer,
        ParameterBagTransformer $parameterBadTransformer
    ) {
        $this->requestLoader = $requestLoader;
        $this->serializer = $serializer;
        $this->parameterBadTransformer = $parameterBadTransformer;
    }

    /**
     * @Route("/api/posts/{postId}", name="api_read_post", methods={"GET"})
     * @param Request $request
     * @throws PostNotFoundException
     * @throws UnknownQueryException
     * @return Response
     */
    public function read(Request $request): Response
    {
        $post = $this->requestLoader->load($request);
        $context = $this->parameterBadTransformer->transformQueryToContext($request->query);

        return new Response(
            $this->serializer->serialize($post, 'json', $context),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}