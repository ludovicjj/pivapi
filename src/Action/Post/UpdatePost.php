<?php


namespace App\Action\Post;

use App\Domain\Command\UpdatePostCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UpdatePost
{
    /** @var SerializerInterface $serializer */
    private $serializer;

    public function __construct(
        SerializerInterface $serializer
    )
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/posts/{postId}", name="api_update_post", methods={"POST"})
     * @param Request $request
     */
    public function update(Request $request)
    {
        $data = $request->getContent();
        $command = $this->serializer->deserialize($data, UpdatePostCommand::class, 'json');
    }
}