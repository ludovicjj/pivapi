<?php


namespace App\Action\Post;


use App\Domain\Command\CreatePostCommand;
use App\Domain\Core\ConstraintValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use League\Tactician\CommandBus;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Domain\Exceptions\ValidatorException;

class CreatePost
{
    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var CommandBus $commandBus */
    private $commandBus;

    /** @var ValidatorInterface $validator */
    private $validator;

    public function __construct(
        SerializerInterface $serializer,
        CommandBus $commandBus,
        ValidatorInterface $validator
    ) {
        $this->serializer = $serializer;
        $this->commandBus = $commandBus;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/posts", name="api_create_post", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->getContent();

        /** @var CreatePostCommand $command */
        $command = $this->serializer->deserialize($data, CreatePostCommand::class, 'json');
        $command->setPostId(uniqid());

        $constraintViolationList = $this->validator->validate($command);
        ConstraintValidator::handleViolation($constraintViolationList);

        dd('payload is valid');

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