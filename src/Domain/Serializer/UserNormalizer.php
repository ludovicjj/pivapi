<?php

namespace App\Domain\Serializer;

use App\Domain\Entity\User;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements ContextAwareNormalizerInterface
{
    /** @var ObjectNormalizer $objectNormalizer */
    private $objectNormalizer;

    const OBJECT_TYPE = 'user';

    const ALLOWED_PUBLIC_ATTRIBUTES = [
        'id',
        'firstname',
        'lastname',
        'email',
        'roles'
    ];

    public function __construct(
        ObjectNormalizer $objectNormalizer
    ) {
        $this->objectNormalizer = $objectNormalizer;
    }

    /**
     * @param User $data
     * @param null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof User;
    }

    /**
     * @inheritDoc
     */
    public function normalize($user, $format = null, array $context = [])
    {
        /*$context['query']['fields'][self::OBJECT_TYPE][] = 'id';*/
        /*$context['attributes'] = $publicAttributes;*/
        $objectNormalizerContext = ['attributes' => []];
        $allowAttributes = $context['query']['fields'][self::OBJECT_TYPE];
        $objectNormalizerContext['attributes'] = $this->filterPublicAttributes($allowAttributes);

        return  $this->objectNormalizer->normalize($user, $format, $objectNormalizerContext);
    }

    private function filterPublicAttributes($attributes){
        return array_filter(array_unique($attributes), function($attribute){
            return in_array($attribute, self::ALLOWED_PUBLIC_ATTRIBUTES);
        });
    }
}