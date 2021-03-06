<?php


namespace App\Domain\Serializer\Includes;


use App\Domain\Entity\AbstractEntity;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Domain\Builder\HateoasBuilder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class HateoasNormalizer
{
    /** @var NormalizerInterface $normalizer */
    private $normalizer;

    /** @var HateoasBuilder $hateoasBuilder */
    private $hateoasBuilder;

    /** @var RequestStack $requestStack */
    private $requestStack;

    /** @var PropertyAccessor $propertyAccessor */
    private $propertyAccessor;

    public function __construct(
        NormalizerInterface $normalizer,
        HateoasBuilder $hateoasBuilder,
        RequestStack $requestStack
    ) {
        $this->normalizer = $normalizer;
        $this->hateoasBuilder = $hateoasBuilder;
        $this->requestStack = $requestStack;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param AbstractEntity $object
     * @param null $format
     * @param array $context
     * @param array $allowedIncludes
     * @return array
     * @throws ExceptionInterface
     */
    public function normalizeIncludes(
        AbstractEntity $object,
        $format,
        array $context = [],
        array $allowedIncludes = []
    ) {
        return $this->getIncludes($object, $format, $context, $allowedIncludes);
    }


    /**
     * @param AbstractEntity $object
     * @param null $format
     * @param array $context
     * @param array $allowedLinks
     * @return array
     * @throws ExceptionInterface
     */
    private function getIncludes(AbstractEntity $object, $format, array $context, array $allowedLinks)
    {

        $links['_links'] = array_reduce($allowedLinks, function ($carry, $link) use ($object) {
            return $carry +
                [
                    $link['type'] => $this->hateoasBuilder->build(
                        $link['type'],
                        $link['route'],
                        $this->getParameters($link['parameters'], $object)
                    )
                ];
        }, []);

        return $this->normalizer->normalize($links, $format, $context);
    }

    private function getParameters(array $parameters, AbstractEntity $object): array
    {
        if (empty($parameters)) {
            return [];
        }

        $requestAttributes = [];
        $requestQuery = [];

        array_walk($parameters, function($property, $key) use ($object, &$requestAttributes, &$requestQuery) {

            if ($key == 'request' && $property == 'query') {
                $requestQuery = $this->requestStack->getCurrentRequest()->query->all();
            } else {
                $requestAttributes[$key] = $this->propertyAccessor->getValue($object, $property);
            }
        });
       return array_merge($requestAttributes, $requestQuery);
    }
}