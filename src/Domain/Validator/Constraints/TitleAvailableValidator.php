<?php


namespace App\Domain\Validator\Constraints;


use App\Domain\Command\AbstractCommand;
use App\Domain\Entity\Post;
use App\Domain\Repository\PostRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;


class TitleAvailableValidator extends ConstraintValidator
{
    /** @var PostRepository $postRepository */
    private $postRepository;

    public function __construct(
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
    }

    /**
     * @param mixed $command
     * @param Constraint $constraint
     */
    public function validate($command, Constraint $constraint): void
    {
        if (!$constraint instanceof TitleAvailable) {
            throw new UnexpectedTypeException($constraint, TitleAvailable::class);
        }

        /** @var TitleAvailable $titleConstraint */
        $titleConstraint = $constraint;

        /** @var null|Post $post */
        $post = $this->postRepository->findOneBy(['title' => $command->getTitle()]);

        if (!$this->isSamePost($command, $post)) {
            $this->context
                ->buildViolation($titleConstraint->message)
                ->atPath('title')
                ->addViolation();
        }
    }

    /**
     * @param $command
     * @param Post|null $post
     * @return bool
     */
    private function isSamePost(AbstractCommand $command, ?Post $post): bool
    {
        if (!is_null($post) && $post instanceof Post && $command->getPostId() === $post->getId()) {
            return true;
        }

        return false;
    }
}