<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator;

use ApiClients\Foundation\Resource\ResourceInterface;
use React\Promise\CancellablePromiseInterface;

interface HandlerInterface
{
    /**
     * HandlerInterface constructor.
     * @param Hydrator $hydrator
     */
    public function __construct(Hydrator $hydrator);

    /**
     * @param AnnotationInterface $annotation
     * @param array $json
     * @param ResourceInterface $object
     * @return CancellablePromiseInterface
     */
    public function hydrate(
        AnnotationInterface $annotation,
        array $json,
        ResourceInterface $object
    ): CancellablePromiseInterface;

    /**
     * @param AnnotationInterface $annotation
     * @param ResourceInterface $object
     * @param array $json
     * @return CancellablePromiseInterface
     */
    public function extract(
        AnnotationInterface $annotation,
        ResourceInterface $object,
        array $json
    ): CancellablePromiseInterface;
}
