<?php


namespace App\Action\Post;

use App\Domain\Command\UpdatePostCommand;
use App\Domain\Core\ConstraintValidator;
use App\Domain\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdatePost
{
    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var ValidatorInterface $validator */
    private $validator;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator
    )
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/posts/{postId}", name="api_update_post", methods={"POST"})
     * @param Request $request
     * @throws ValidatorException
     */
    public function update(Request $request)
    {
        $data = $request->getContent();

        /** @var UpdatePostCommand $command */
        $command = $this->serializer->deserialize($data, UpdatePostCommand::class, 'json');
        $command->setPostId($request->attributes->get('postId'));

        $constraintList = $this->validator->validate($command);
        ConstraintValidator::handleViolation($constraintList);
    }
}