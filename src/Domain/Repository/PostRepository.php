<?php


namespace App\Domain\Repository;


use App\Domain\Entity\Post;

class PostRepository extends AbstractRepository
{
    public function getEntityClassName(): string
    {
        return Post::class;
    }
}