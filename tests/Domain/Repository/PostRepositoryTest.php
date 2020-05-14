<?php


namespace App\Tests\Domain\Repository;


use App\Domain\Entity\Post;
use App\Domain\Repository\PostRepository;
use App\Domain\Search\OrderSearch;
use App\Domain\Search\PostSearch;
use App\Tests\Domain\IntegrationTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Domain\Exceptions\UnknownDirectionException;
use App\Domain\Exceptions\UnknownOrderException;

class PostRepositoryTest extends KernelTestCase
{
    use IntegrationTestTrait;

    /**
     * @throws UnknownDirectionException
     * @throws UnknownOrderException
     */
    public function testSearchPostOrderByCreatedAt()
    {
        $this->loadFixture(
            __DIR__ . '/../../Fixtures/Post/searchPost.yaml'
        );

        /** @var PostRepository $commentRepository */
        $commentRepository = self::$container->get(PostRepository::class);

        $posts = $commentRepository->search(
            new PostSearch(
                [],
                [new OrderSearch(PostSearch::ORDER_BY_CREATED_AT, 'asc')],
                1,
                5
            )
        );

        $arrayPosts = iterator_to_array($posts);

        $postIds = array_map(function(Post $post) {
            return $post->getId();
        }, $arrayPosts);

        $this->assertCount(3, $arrayPosts);
        $this->assertEquals('post1', $postIds[0]);
        $this->assertEquals('post3', $postIds[1]);
        $this->assertEquals('post2', $postIds[2]);
    }

    /**
     * @throws UnknownDirectionException
     * @throws UnknownOrderException
     */
    public function testSearchPostOrderByUpdatedAt()
    {
        $this->loadFixture(
            __DIR__ . '/../../Fixtures/Post/searchPost.yaml'
        );

        /** @var PostRepository $commentRepository */
        $commentRepository = self::$container->get(PostRepository::class);

        $posts = $commentRepository->search(
            new PostSearch(
                [],
                [new OrderSearch(PostSearch::ORDER_BY_UPDATED_AT, 'asc')],
                1,
                5
            )
        );

        $arrayPosts = iterator_to_array($posts);

        $postIds = array_map(function(Post $post) {
            return $post->getId();
        }, $arrayPosts);

        $this->assertCount(3, $arrayPosts);
        $this->assertEquals('post2', $postIds[0]);
        $this->assertEquals('post3', $postIds[1]);
        $this->assertEquals('post1', $postIds[2]);
    }

    /**
     * @throws UnknownDirectionException
     * @throws UnknownOrderException
     */
    public function testSearchPostOrderByTitle()
    {
        $this->loadFixture(
            __DIR__ . '/../../Fixtures/Post/searchPost.yaml'
        );

        /** @var PostRepository $commentRepository */
        $commentRepository = self::$container->get(PostRepository::class);

        $posts = $commentRepository->search(
            new PostSearch(
                [],
                [new OrderSearch(PostSearch::ORDER_BY_TITLE, 'asc')],
                1,
                5
            )
        );

        $arrayPosts = iterator_to_array($posts);

        $postIds = array_map(function(Post $post) {
            return $post->getId();
        }, $arrayPosts);

        $this->assertCount(3, $arrayPosts);
        $this->assertEquals('post2', $postIds[0]);
        $this->assertEquals('post1', $postIds[1]);
        $this->assertEquals('post3', $postIds[2]);
    }

    /**
     * @throws UnknownDirectionException
     * @throws UnknownOrderException
     */
    public function testSearchPostOrderByUser()
    {
        $this->loadFixture(
            __DIR__ . '/../../Fixtures/Post/searchPost.yaml'
        );

        /** @var PostRepository $commentRepository */
        $commentRepository = self::$container->get(PostRepository::class);

        $posts = $commentRepository->search(
            new PostSearch(
                [],
                [new OrderSearch(PostSearch::ORDER_BY_USER, 'asc')],
                1,
                5
            )
        );

        $arrayPosts = iterator_to_array($posts);

        $postIds = array_map(function(Post $post) {
            return $post->getId();
        }, $arrayPosts);

        $this->assertCount(3, $arrayPosts);
        $this->assertEquals('post3', $postIds[0]);
        $this->assertEquals('post1', $postIds[1]);
        $this->assertEquals('post2', $postIds[2]);
    }
}