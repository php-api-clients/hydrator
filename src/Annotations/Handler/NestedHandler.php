<?php
declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\Annotations\Handler;

use ApiClients\Foundation\Hydrator\AnnotationInterface;
use ApiClients\Foundation\Hydrator\Annotations\Nested;
use ApiClients\Foundation\Hydrator\HandlerInterface;
use ApiClients\Foundation\Resource\ResourceInterface;

class NestedHandler extends AbstractHandler implements HandlerInterface
{
    public function hydrate(AnnotationInterface $annotation, array $json, ResourceInterface $object): array
    {
        if (!($annotation instanceof Nested)) {
            return $json;
        }

        foreach ($annotation->properties() as $property) {
            if (!isset($json[$property])) {
                $json[$property] = null;
                continue;
            }

            if (!is_array($json[$property])) {
                continue;
            }

            $json[$property] = $this->getHydrator()->hydrate($annotation->get($property), $json[$property]);
        }

        return $json;
    }

    public function extract(AnnotationInterface $annotation, ResourceInterface $object, array $json): array
    {
        if (!($annotation instanceof Nested)) {
            return $json;
        }

        foreach ($annotation->properties() as $property) {
            if (!($json[$property] instanceof ResourceInterface)) {
                continue;
            }

            $json[$property] = $this->getHydrator()->extract($annotation->get($property), $json[$property]);
        }

        return $json;
    }
}
