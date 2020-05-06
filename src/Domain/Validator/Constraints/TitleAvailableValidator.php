<?php


namespace App\Domain\Validator\Constraints;


use App\Domain\Entity\Post;
use App\Domain\Repository\PostRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TitleAvailableValidator extends ConstraintValidator
{
    /** @var PostRepository $postRepository */
    private $postRepository;

    public function __construct(
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
    }

    public function validate($command, Constraint $constraint)
    {
        /** @var null|Post $post */
        $post = $this->postRepository->findOneBy(['title' => $command->getTitle()]);

        if (!is_null($post) && $post instanceof Post) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('title')
                ->addViolation();
        }
    }
}