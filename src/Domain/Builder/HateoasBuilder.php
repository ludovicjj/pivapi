<?php


namespace App\Domain\Builder;


use App\Domain\Core\Hateoas\AbstractLink;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HateoasBuilder
{
    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /**
     * HateoasBuilder constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param string $type
     * @param string $route
     * @param array $params
     * @return AbstractLink
     */
    public function build(string $type, string $route, array $params = []): AbstractLink
    {
        return LinkBuilder::build(
            $type,
            $this->urlGenerator->generate($route, $params, UrlGeneratorInterface::ABSOLUTE_URL)
        );
    }
}