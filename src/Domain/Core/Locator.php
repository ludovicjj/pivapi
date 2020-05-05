<?php

namespace App\Domain\Core;

use Psr\Container\ContainerInterface;
use League\Tactician\Handler\Locator\HandlerLocator;

class Locator implements HandlerLocator
{
    const PATH_PREFIX = 'App\\Domain\\Handler\\';

    /** @var ContainerInterface $container */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $commandName
     * @return mixed|object
     */
    public function getHandlerForCommand($commandName)
    {
        $handlerName = self::PATH_PREFIX.substr($commandName, strrpos($commandName, '\\') + 1).'Handler';

        return $this->container->get($handlerName);
    }
}