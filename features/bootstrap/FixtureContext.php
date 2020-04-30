<?php

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger as DoctrineOrmPurger;
use Nelmio\Alice\Loader\NativeLoader;

class FixtureContext implements Context
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Given I load the fixture :fixtureName in :folderName folder
     * @param $folderName
     * @param $fixtureName
     */
    public function iLoadTheFixture($folderName, $fixtureName)
    {
        $loader = new NativeLoader();

        $objectSet = $loader->loadFile(__DIR__.'/../fixtures/'. $folderName . "/" . $fixtureName . ".yaml");

        foreach ($objectSet->getObjects() as $object) {
            $this->entityManager->persist($object);
        }

        $this->entityManager->flush();
    }

    /**
     * @BeforeScenario
     */
    public function clearDatabase()
    {
        $ormPurger = new DoctrineOrmPurger($this->entityManager);
        $ormPurger->purge();
    }
}