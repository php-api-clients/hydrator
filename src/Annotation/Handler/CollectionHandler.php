<?php
declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\Annotation\Handler;

use ApiClients\Foundation\Hydrator\AnnotationInterface;
use ApiClients\Foundation\Hydrator\Annotation\Collection;
use ApiClients\Foundation\Hydrator\HandlerInterface;
use ApiClients\Foundation\Resource\ResourceInterface;

class CollectionHandler extends AbstractHandler implements HandlerInterface
{
    public function hydrate(AnnotationInterface $annotation, array $json, ResourceInterface $object): array
    {
        if (!($annotation instanceof Collection)) {
            return $json;
        }

        foreach ($annotation->properties() as $property) {
            if (!isset($json[$property])) {
                $json[$property] = [];
                continue;
            }

            $array = $json[$property];

            if (!is_array($array)) {
                continue;
            }

            $json[$property] = [];
            foreach ($array as $resource) {
                if ($resource === null) {
                    continue;
                }

                $json[$property][] = $this->getHydrator()->hydrate($annotation->get($property), $resource);
            }
        }

        return $json;
    }

    public function extract(AnnotationInterface $annotation, ResourceInterface $object, array $json): array
    {
        if (!($annotation instanceof Collection)) {
            return $json;
        }

        foreach ($annotation->properties() as $property) {
            $array = $json[$property];
            $json[$property] = [];
            foreach ($array as $resource) {
                $json[$property][] = $this->getHydrator()->extract($annotation->get($property), $resource);
            }
        }

        return $json;
    }
}
