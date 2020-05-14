<?php


namespace App\Domain\Core\Fixtures\Loader;


use Faker\Factory;
use Faker\Generator;
use Fidry\AliceDataFixtures\LoaderInterface;
use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Nelmio\Alice\Faker\Provider\AliceProvider;
use Nelmio\Alice\Loader\NativeLoader;

class Loader extends NativeLoader implements LoaderInterface
{
    /**
     * You can set the default locale to use by configuring the locale value used by Faker generator.
     * With NativeLoader, this can be done by overriding the createFakerGenerator() method.
     *
     * @return Generator
     */
    protected function createFakerGenerator(): Generator
    {
        $generator = Factory::create('fr_FR');
        $generator->addProvider(new AliceProvider());
        $generator->seed($this->getSeed());

        return $generator;
    }


    /**
     * Loads the fixtures files and return the loaded objects.
     *
     * @param string[] $fixturesFiles Path to the fixtures files to loads.
     * @param array $parameters
     * @param array $objects
     * @param PurgeMode|null $purgeMode
     *
     * @return object[]
     */
    public function load(
        array $fixturesFiles,
        array $parameters = [],
        array $objects = [],
        PurgeMode $purgeMode = null
    ): array {
        return $this->loadFiles($fixturesFiles, $parameters, $objects)->getObjects();
    }
}