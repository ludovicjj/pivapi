<?php


namespace App\Domain\Serializer\Includes;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IncludesNormalizer
{
    /** @var PropertyAccessor $propertyAccessor */
    private $propertyAccessor;

    /** @var NormalizerInterface $normalizer */
    private $normalizer;

    public function __construct(
        NormalizerInterface $normalizer
    ) {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->normalizer = $normalizer;
    }

    public function normalizeIncludes(
        $object,
        $format,
        array $context = [],
        array $allowedIncludes = []
    )
    {
        if (!isset($context['query']['includes'])) {
            return [];
        }

        $context['query']['includes'] = array_unique(
            array_filter($context['query']['includes'], function($include) use ($allowedIncludes) {
                return in_array(explode('.', $include)[0], $allowedIncludes);
            })
        );

        return array_map(function($include) use ($object, $format, $context){
            return $this->normalizer->normalize(
                $this->propertyAccessor->getValue($object, $include),
                $format,
                $this->getSubContext($context, $include)
            );
        }, $this->getRootIncludes($context));
    }

    /**
     * @param $context
     * @return array
     */
    private function getRootIncludes($context): array
    {
        return array_reduce($context['query']['includes'], function($carry, $include){
            $rootInclude = explode('.', $include)[0];
            return $carry + array($rootInclude => $rootInclude);
        }, []);
    }

    private function getSubContext($context, $include): array
    {
        $subContext = array_filter($context['query']['includes'], function($subInclude) use ($include) {
            return strpos($subInclude, $include.'.') === 0;
        });

        $context['query']['includes'] = array_map(function($subContext) {
            return substr($subContext, strpos($subContext, '.') + 1);
        }, $subContext);

        return $context;
    }
}