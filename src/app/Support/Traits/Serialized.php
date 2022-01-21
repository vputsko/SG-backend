<?php

declare(strict_types = 1);

namespace App\Support\Traits;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

trait Serialized
{

    /**
     * Serialize all model or only specific attributes
     *
     * @param array|object $data
     * @return string
     */
    protected function toJson($data, array $attributes = []): string
    {
        $encoders = [new XmlEncoder, new JsonEncoder];
        $normalizers = [new ObjectNormalizer];

        $serializer = new Serializer($normalizers, $encoders);

        if (count($attributes)) {
            $attributes = [AbstractNormalizer::ATTRIBUTES => $attributes];
        }

        return $serializer->serialize($data, 'json', $attributes);
    }

}
