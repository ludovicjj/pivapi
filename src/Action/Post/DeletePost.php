<?php


namespace App\Action\Post;


use App\Domain\Command\DeletePostCommand;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DeletePost
{
    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var CommandBus $commandBus */
    private $commandBus;

    public function __construct(
        SerializerInterface $serializer,
        CommandBus $commandBus
    )
    {
        $this->serializer = $serializer;
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/api/posts/{postId}", name="api_delete_post", methods={"DELETE"})
     * @param Request $request
     */
    public function delete(Request $request)
    {
        $postId = $request->attributes->get('postId');
        $command = new DeletePostCommand($postId);

        $this->commandBus->handle($command);

        dd('in working...');
    }
}