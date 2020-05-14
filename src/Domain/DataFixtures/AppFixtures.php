<?php

namespace App\Domain\DataFixtures;

use App\Domain\Core\Fixtures\Loader\Loader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

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
        $fixtures = $this->loader->load([__DIR__ . '/appFixture.yaml']);

        foreach ($fixtures as $fixture)
        {
            $manager->persist($fixture);
        }

        $manager->flush();
    }
}
