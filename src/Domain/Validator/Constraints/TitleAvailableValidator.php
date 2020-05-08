<?php


namespace App\Domain\Validator\Constraints;


use App\Domain\Command\AbstractCommand;
use App\Domain\Command\CreatePostCommand;
use App\Domain\Command\UpdatePostCommand;
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

        if (!$this->isValidTitle($command, $post)) {
            $this->context
                ->buildViolation($titleConstraint->message)
                ->atPath('title')
                ->addViolation();
        }
    }

    /**
     * @param AbstractCommand $command
     * @param Post|null $post
     * @return bool
     */
    private function isValidTitle(AbstractCommand $command, ?Post $post): bool
    {
        if (
            $command instanceof CreatePostCommand
            && is_null($post)
        ) {
            return true;
        }

        if (
            !is_null($post)
            && $command instanceof UpdatePostCommand
            && $post instanceof Post
            && $command->getPostId() === $post->getId()
        ) {
            return true;
        }

        return false;
    }
}