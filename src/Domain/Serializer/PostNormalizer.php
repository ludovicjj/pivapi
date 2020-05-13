<?php


namespace App\Domain\Serializer;


use App\Domain\Entity\AbstractEntity;
use App\Domain\Entity\Post;
use App\Domain\Exceptions\NormalizerException;
use App\Domain\Serializer\Includes\IncludesNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use ArrayObject;

class PostNormalizer implements ContextAwareNormalizerInterface
{
    /** @var ObjectNormalizer $objectNormalizer */
    private $objectNormalizer;

    /** @var IncludesNormalizer $includesNormalizer */
    private $includesNormalizer;

    const OBJECT_TYPE = 'post';

    const ALLOWED_ATTRIBUTES = [
        'id',
        'title',
        'content'
    ];

    const ALLOWED_INCLUDES = [
        'user',
    ];

    public function __construct(
        ObjectNormalizer $objectNormalizer,
        IncludesNormalizer $includesNormalizer
    ) {
        $this->objectNormalizer = $objectNormalizer;
        $this->includesNormalizer = $includesNormalizer;
    }

    /**
     * @param Post $data
     * @param null $format
     * @param array $context
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Post;
    }

    /**
     * @param mixed $post
     * @param null $format
     * @param array $context
     * @return array
     * @throws ExceptionInterface
     * @throws NormalizerException
     */
    public function normalize($post, $format = null, array $context = [])
    {
        if (!isset($context['query']['fields'][self::OBJECT_TYPE])) {
            throw new NormalizerException(
                sprintf('Missing index %s in array fields', self::OBJECT_TYPE),
                400
            );
        }

        $allowAttributes = $context['query']['fields'][self::OBJECT_TYPE];
        $objectNormalizerContext['attributes'] = $this->filterAllowAttributes($allowAttributes);

        /** @var array $postNormalized */
        $postNormalized = $this->objectNormalizer->normalize($post, $format, $objectNormalizerContext);

        /** @var array $includeNormalized */
        $includeNormalized = $this->includesNormalizer->normalizeIncludes(
            $post,
            $format,
            $context,
            self::ALLOWED_INCLUDES
        );

        return array_merge($postNormalized, $includeNormalized);
    }

    /**
     * @param array $attributes
     * @return array
     */
    private function filterAllowAttributes($attributes): array
    {
        return array_filter(array_unique($attributes), function($attribute){
            return in_array($attribute, self::ALLOWED_ATTRIBUTES);
        });
    }
}