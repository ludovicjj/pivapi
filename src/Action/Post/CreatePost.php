<?php


namespace App\Action\Post;


use App\Domain\Command\CreatePostCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use League\Tactician\CommandBus;

class CreatePost
{
    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var CommandBus $commandBus */
    private $commandBus;

    public function __construct(
        SerializerInterface $serializer,
        CommandBus $commandBus
    ) {
        $this->serializer = $serializer;
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/api/posts", name="api_create_post", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->getContent();

        /** @var CreatePostCommand $command */
        $command = $this->serializer->deserialize($data, CreatePostCommand::class, 'json');
        $command->setPostId(uniqid());

        $this->commandBus->handle($command);

        return new JsonResponse(
            [
                'data' => [
                    'id' => $command->getPostId()
                ]
            ],
            201
        );
    }
}