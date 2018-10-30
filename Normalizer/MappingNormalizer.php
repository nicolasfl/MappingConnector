<?php

namespace Extensions\Bundle\MappingConnectorBundle\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\scalar;
use Extensions\Bundle\MappingConnectorBundle\Entity\Mapping;

/**
 * Class MappingNormalizer
 *
 * @author                 Nicolas SOUFFLEUR, Akeneo Expert <contact@nicolas-souffleur.com>
 * @license                http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MappingNormalizer implements NormalizerInterface
{

    /**
     * @param object $object
     * @param null   $format
     * @param array  $context
     *
     * @return array
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id'        => $object->getId(),
            'code'      => $object->getCode(),
            'job'    => $object->getJob(),
            'attribute' => $object->getAttribute(),
            'title'    => $object->getTitle(),
        ];
    }

    /**
     * @param mixed $data
     * @param null  $format
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Mapping;
    }
}