<?php

namespace App\Serializer\Normalizer;

use App\Entity\Note;
use App\Entity\Tasks;
use App\Entity\TodoLists;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PublicDataNormalizer implements NormalizerInterface
{

    private $packages;
    private $objectNormalizer;
    public function __construct(Packages $packages, ObjectNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
        $this->packages = $packages;
    }
    public function normalize($object, ?string $format = null, array $context = [])
    {
        #ignored_attributes -> ignores selected data and wont show it on the return
        $context['ignored_attributes'] = ['user' => 'password', "email"];
        $data = $this->objectNormalizer->normalize($object, $format, $context);

        return $data;
    }
    public function supportsNormalization($data, ?string $format = null)
    {
        return $data instanceof TodoLists || $data instanceof Tasks || $data instanceof Note;
    }
}
