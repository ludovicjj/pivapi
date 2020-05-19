<?php

namespace App\Domain\Serializer;

use App\Domain\Entity\User;
use App\Domain\Exceptions\NormalizerException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

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
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param mixed $user
     * @param null $format
     * @param array $context
     *
     * @return array|\ArrayObject|bool|float|int|mixed|string|null
     * @throws ExceptionInterface
     */
    public function normalize($user, $format = null, array $context = [])
    {
        $objectNormalizerContext = ['attributes' => []];
        $allowAttributes = $context['query']['fields'][self::OBJECT_TYPE];
        $objectNormalizerContext['attributes'] = $this->filterPublicAttributes($allowAttributes);

        return  $this->objectNormalizer->normalize($user, $format, $objectNormalizerContext);
    }

    /**
     * @param array $attributes
     * @return array
     */
    private function filterPublicAttributes($attributes): array
    {
        return array_filter(array_unique($attributes), function($attribute){
            if (!in_array($attribute, self::ALLOWED_PUBLIC_ATTRIBUTES)) {
                throw new NormalizerException(
                    sprintf(
                        'Invalid user attributes. Allowed attributes are : %s',
                        implode(', ', self::ALLOWED_PUBLIC_ATTRIBUTES)
                    ),
                    400
                );
            } else {
                return true;
            }
        });
    }
}