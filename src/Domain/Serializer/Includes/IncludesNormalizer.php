<?php


namespace App\Domain\Serializer\Includes;

use App\Domain\Entity\AbstractEntity;
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

    /**
     * @param AbstractEntity $object
     * @param null $format
     * @param array $context
     * @param array $allowedIncludes
     * @return AbstractEntity[]|array
     */
    public function normalizeIncludes(
        AbstractEntity $object,
        $format,
        array $context = [],
        array $allowedIncludes = []
    ) {
        $context['query']['includes'] = $this->filterIncludes($context, $allowedIncludes);
        return $this->getIncludes($object, $format, $context);
    }

    /**
     * @param array $context
     * @param array $allowedIncludes
     * @return array|string[]
     */
    private function filterIncludes(array $context, array $allowedIncludes): array
    {
        return array_filter(array_unique($context['query']['includes']), function ($include) use ($allowedIncludes) {
            return in_array(explode('.', $include)[0], $allowedIncludes);
        });
    }

    /**
     * @param AbstractEntity $object
     * @param null $format
     * @param array $context
     * @return array
     */
    private function getIncludes(AbstractEntity $object, $format, array $context)
    {
        return array_map(function($root) use ($object, $format, $context) {
            return $this->normalizer->normalize(
                $this->getSubObject($object, $root),
                $format,
                $this->getSubContext($context, $root)
            );
        }, $this->getRootIncludes($context));
    }

    /**
     * @param array $context
     * @return array|string[]
     */
    private function getRootIncludes(array $context): array
    {
        return array_reduce($context['query']['includes'], function($carry, $include) {
            $rootInclude = explode('.', $include)[0];
            return $carry + [$rootInclude => $rootInclude];
        }, []);
    }

    /**
     * @param AbstractEntity $object
     * @param string $include
     * @return AbstractEntity
     */
    private function getSubObject(AbstractEntity$object, string $include): AbstractEntity
    {
        return $this->propertyAccessor->getValue($object, $include);
    }

    /**
     * @param array $context
     * @param string $root
     * @return array
     */
    private function getSubContext(array $context, string $root): array
    {
        /** @var array $subContextWithRoot */
        $subContextWithRoot = array_filter($context['query']['includes'], function($subInclude) use ($root) {
            return strpos($subInclude, $root . '.') === 0;
        });

        /** @var array $subContext */
        $subContext = array_map(function($subContextWithRoot) {
            return substr($subContextWithRoot, strpos($subContextWithRoot, '.') + 1);
        }, $subContextWithRoot);

        $context['query']['fields'][$root] = $subContext;

        return $context;
    }
}