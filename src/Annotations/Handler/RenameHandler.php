<?php
declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\Annotations\Handler;

use ApiClients\Foundation\Hydrator\Annotations\Rename;
use ApiClients\Foundation\Hydrator\AnnotationInterface;
use ApiClients\Foundation\Hydrator\HandlerInterface;
use ApiClients\Foundation\Resource\ResourceInterface;

class RenameHandler extends AbstractHandler implements HandlerInterface
{
    public function hydrate(AnnotationInterface $annotation, array $json, ResourceInterface $object): array
    {
        if (!($annotation instanceof Rename)) {
            return $json;
        }

        foreach ($annotation->properties() as $property) {
            if (!isset($json[$annotation->get($property)])) {
                continue;
            }

            $json[$property] = $json[$annotation->get($property)];
            unset($json[$annotation->get($property)]);
        }

        return $json;
    }

    public function extract(AnnotationInterface $annotation, ResourceInterface $object, array $json): array
    {
        if (!($annotation instanceof Rename)) {
            return $json;
        }

        foreach ($annotation->properties() as $property) {
            $json[$annotation->get($property)] = $json[$property];
            unset($json[$property]);
        }

        return $json;
    }
}
