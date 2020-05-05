<?php


namespace App\Domain\Repository;


use App\Domain\Entity\Post;
use Doctrine\ORM\ORMException;

class PostRepository extends AbstractRepository
{
    public function getEntityClassName(): string
    {
        return Post::class;
    }

    /**
     * @param Post $post
     * @throws ORMException
     */
    public function persist(Post $post)
    {
        $this->_em->persist($post);
    }
}