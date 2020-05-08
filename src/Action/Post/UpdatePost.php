<?php


namespace App\Action\Post;

use App\Domain\Command\UpdatePostCommand;
use App\Domain\Core\ConstraintValidator;
use App\Domain\Exceptions\ValidatorException;
use App\Domain\Repository\PostRepository;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdatePost
{
    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var ValidatorInterface $validator */
    private $validator;

    /** @var CommandBus $commandBus */
    private $commandBus;

    /** @var PostRepository $postRepository */
    private $postRepository;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        CommandBus $commandBus,
        PostRepository $postRepository
    )
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->commandBus = $commandBus;
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/api/posts/{postId}", name="api_update_post", methods={"POST"})
     * @param Request $request
     * @throws ValidatorException
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $data = $request->getContent();
        $post = $this->postRepository->findOneBy(['id' => $request->attributes->get('postId')]);

        if (is_null($post)) {
            throw new NotFoundHttpException(
                sprintf('Post with id %s not found', $request->attributes->get('postId'))
            );
        }

        /** @var UpdatePostCommand $command */
        $command = $this->serializer->deserialize($data, UpdatePostCommand::class, 'json');
        $command->setPostId($request->attributes->get('postId'));

        $constraintList = $this->validator->validate($command);
        ConstraintValidator::handleViolation($constraintList);

        $this->commandBus->handle($command);

        return new JsonResponse(['data' => []], 200);
    }
}