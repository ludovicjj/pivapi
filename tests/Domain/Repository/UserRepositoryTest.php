<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger as DoctrineOrmPurger;

class UserRepositoryTest extends KernelTestCase
{
    /** @var LoaderInterface */
    private $loader;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        self::bootKernel();
        $this->loader = self::$container->get('fidry_alice_data_fixtures.loader.doctrine');
        $this->entityManager = self::$container->get('doctrine')->getManager();
    }

    public function tearDown()
    {
        $purger = new DoctrineOrmPurger($this->entityManager);
        $purger->purge();

        //  ensure the kernel is shut down before calling the method
        parent::tearDown();
    }

    public function testCount()
    {
        $this->loader->load([
            __DIR__ . '/../../Fixtures/User/createUser.yml'
        ]);

        $users = $this->entityManager->getRepository(User::class)->findAll();
        $this->assertEquals(10, count($users));
    }
}