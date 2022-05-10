<?php

namespace App\Serializer\Normalizer;

use App\Entity\TodoLists;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\Package;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TodoListNormalizer implements NormalizerInterface
{
    private $packages;
    private $objectNormalizer;
    public function __construct(Packages $packages, ObjectNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
        $this->packages = $packages;
    }

    public function normalize($object, $format = null, array $context = [])
    {

        $object->setBackgroundPath(
            $this->packages->getUrl($object->getBackgroundPath(), 'backgrounds')
        );
        $context['ignored_attributes'] = ['user'];

        $data = $this->objectNormalizer->normalize($object, $format, $context);
        return $data;
    }

    public function supportsNormalization($data, ?string $format = null)
    {
        return $data instanceof TodoLists;
    }
}
