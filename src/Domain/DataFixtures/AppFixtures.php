<?php

namespace App\Domain\DataFixtures;

use App\Domain\Core\Fixtures\Loader\Loader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Fidry\AliceDataFixtures\Bridge\Doctrine\Persister\ObjectManagerPersister;
use Fidry\AliceDataFixtures\Loader\PersisterLoader;
use Psr\Log\NullLogger;

class AppFixtures extends Fixture
{
    /** @var Loader $loader */
    private $loader;

    public function __construct(
      Loader $loader
    ) {
        $this->loader = $loader;
    }

    public function load(ObjectManager $manager): void
    {
        $persister = new PersisterLoader(
            $this->loader,
            new ObjectManagerPersister($manager),
            new NullLogger(),
            []
        );

        $persister->load([__DIR__ . '/appFixture.yaml']);
    }
}
