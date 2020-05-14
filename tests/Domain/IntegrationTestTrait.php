<?php


namespace App\Tests\Domain;


use Doctrine\Common\DataFixtures\Purger\ORMPurger as DoctrineOrmPurger;
use Doctrine\ORM\EntityManagerInterface;
use Fidry\AliceDataFixtures\Bridge\Doctrine\Persister\ObjectManagerPersister;
use Fidry\AliceDataFixtures\Loader\PersisterLoader;
use Psr\Log\NullLogger;

trait IntegrationTestTrait
{
    /** @var EntityManagerInterface entityManager */
    private $entityManager;

    /**
     * @before
     */
    protected function purgeDatabase()
    {
        self::bootKernel();
        $this->entityManager = self::$container->get('doctrine.orm.default_entity_manager');
        $purger = new DoctrineOrmPurger($this->entityManager);
        $purger->purge();
    }

    /**
     * @param $file
     * @return array|object[]
     */
    protected function loadFixture($file)
    {
        $persister = new PersisterLoader(
            self::$container->get('alice.fixtures.loader'),
            new ObjectManagerPersister($this->entityManager),
            new NullLogger(),
            []
        );

        $fixtures = $persister->load([$file]);
        $this->entityManager->clear();

        return $fixtures;
    }
}