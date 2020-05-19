<?php


namespace App\Domain\Serializer;


use App\Domain\Builder\LinkBuilder;
use App\Domain\Entity\Post;
use App\Domain\Exceptions\NormalizerException;
use App\Domain\Serializer\Includes\IncludesNormalizer;
use App\Domain\Serializer\Includes\HateoasNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class PostNormalizer implements ContextAwareNormalizerInterface
{
    /** @var ObjectNormalizer $objectNormalizer */
    private $objectNormalizer;

    /** @var IncludesNormalizer $includesNormalizer */
    private $includesNormalizer;

    /** @var HateoasNormalizer $hateoasNormalizer */
    private $hateoasNormalizer;

    const OBJECT_TYPE = 'post';

    const ALLOWED_ATTRIBUTES = [
        'id',
        'title',
        'content'
    ];

    const ALLOWED_INCLUDES = [
        'user',
    ];

    const ALLOWED_LINK = [
        [
            'route' => 'api_create_post',
            'type' => LinkBuilder::NEW,
            'parameters' => []
        ],
        [
            'route' => 'api_search_post',
            'type' => LinkBuilder::LIST,
            'parameters' => ['request' => 'query']
        ],
        [
            'route' => 'api_update_post',
            'type' => LinkBuilder::UPDATE,
            'parameters' => ['postId' => 'id']
        ],
        [
            'route' => 'api_delete_post',
            'type' => LinkBuilder::DELETE,
            'parameters' => ['postId' => 'id']
        ],
    ];

    public function __construct(
        ObjectNormalizer $objectNormalizer,
        IncludesNormalizer $includesNormalizer,
        HateoasNormalizer $hateoasNormalizer
    ) {
        $this->objectNormalizer = $objectNormalizer;
        $this->includesNormalizer = $includesNormalizer;
        $this->hateoasNormalizer = $hateoasNormalizer;
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
     */
    public function normalize($post, $format = null, array $context = [])
    {
        $context['query']['fields'][self::OBJECT_TYPE][] = 'id';

        $allowedAttributes = $context['query']['fields'][self::OBJECT_TYPE];
        $objectNormalizerContext['attributes'] = $this->filterAllowedAttributes($allowedAttributes);

        /** @var array $postNormalized */
        $postNormalized = $this->objectNormalizer->normalize($post, $format, $objectNormalizerContext);

        /** @var array $includeNormalized */
        $includeNormalized = $this->includesNormalizer->normalizeIncludes(
            $post,
            $format,
            $context,
            self::ALLOWED_INCLUDES
        );

        /** @var array $hateoasLinks */
        $hateoasLinks = $this->hateoasNormalizer->normalizeIncludes(
            $post,
            $format,
            $context,
            self::ALLOWED_LINK
        );

        return array_merge($postNormalized, $includeNormalized, $hateoasLinks);
    }

    /**
     * @param array $attributes
     * @return array
     */
    private function filterAllowedAttributes($attributes): array
    {
        return array_filter(array_unique($attributes), function($attribute){
            if (!in_array($attribute, self::ALLOWED_ATTRIBUTES)) {
                throw new NormalizerException(
                    sprintf(
                        'Invalid post attributes. Allowed attributes are : %s',
                        implode(', ', self::ALLOWED_ATTRIBUTES)
                    ),
                    400
                );
            } else {
                return true;
            }
        });
    }
}