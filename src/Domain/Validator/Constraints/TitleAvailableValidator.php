<?php


namespace App\Domain\Validator\Constraints;


use App\Domain\Command\AbstractCommand;
use App\Domain\Command\CreatePostCommand;
use App\Domain\Command\UpdatePostCommand;
use App\Domain\Repository\PostRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * @throws NonUniqueResultException
     */
    public function validate($command, Constraint $constraint): void
    {
        if (!$constraint instanceof TitleAvailable) {
            throw new UnexpectedTypeException($constraint, TitleAvailable::class);
        }

        /** @var TitleAvailable $titleConstraint */
        $titleConstraint = $constraint;

        if (!$this->isValidTitle($command)) {
            $this->context
                ->buildViolation($titleConstraint->message)
                ->atPath('title')
                ->addViolation();
        }
    }

    /**
     * @param AbstractCommand $command
     * @return bool
     * @throws NonUniqueResultException
     */
    private function isValidTitle(AbstractCommand $command): bool
    {
        if ($command instanceof CreatePostCommand) {
            $post = $this->postRepository->findOneBy(['title' => $command->getTitle()]);
            return is_null($post);
        }

        if ($command instanceof UpdatePostCommand) {
            $post = $this->postRepository->isUniqueTitle($command->getPostId(), $command->getTitle());
            return is_null($post);
        }

        return false;
    }
}