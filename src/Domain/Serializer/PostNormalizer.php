<?php


namespace App\Domain\Serializer;


use App\Domain\Entity\Post;
use App\Domain\Serializer\Includes\IncludesNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

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
     * @return array|\ArrayObject|bool|float|int|mixed|string|null
     * @throws ExceptionInterface
     */
    public function normalize($post, $format = null, array $context = [])
    {
        $objectNormalizerContext = ['attributes' => []];
        $allowAttributes = $context['query']['fields'][self::OBJECT_TYPE];
        $objectNormalizerContext['attributes'] = $this->filterAllowAttributes($allowAttributes);

        return $this->objectNormalizer->normalize($post, $format, $objectNormalizerContext)
            + $this->includesNormalizer->normalizeIncludes($post, $format, $context, self::ALLOWED_INCLUDES);
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