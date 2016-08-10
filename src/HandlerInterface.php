<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator;

use ApiClients\Foundation\Resource\ResourceInterface;

interface HandlerInterface
{
    public function __construct(Hydrator $hydrator);
    public function hydrate(AnnotationInterface $annotation, array $json, ResourceInterface $object): array;
    public function extract(AnnotationInterface $annotation, ResourceInterface $object, array $json): array;
}
