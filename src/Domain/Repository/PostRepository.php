<?php


namespace App\Domain\Repository;


use App\Domain\Entity\Post;
use App\Domain\Search\PostSearch;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
    public function persist(Post $post): void
    {
        $this->_em->persist($post);
    }

    /**
     * @param mixed $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return object|null|Post
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    /**
     * @param Post $post
     * @throws ORMException
     */
    public function remove(Post $post): void
    {
        $this->_em->remove($post);
    }

    /**
     * @param string $id
     * @param string $title
     * @return Post|null
     * @throws NonUniqueResultException
     */
    public function isUniqueTitle(string $id, string $title): ?Post
    {
        return $this->createQueryBuilder('post')
            ->where('post.id != :id')
            ->andWhere('post.title = :title')
            ->setParameter('id', $id)
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param PostSearch $postSearch
     * @return Paginator
     */
    public function search(PostSearch $postSearch): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('post');

        $queryBuilder->addOrderBy('post.id', 'asc');

        if ($postSearch->getItems() !== null) {
            $queryBuilder
                ->setFirstResult(($postSearch->getPage() - 1) * $postSearch->getItems())
                ->setMaxResults($postSearch->getItems());
        }

        return new Paginator($queryBuilder);
    }
}