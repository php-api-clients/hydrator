<?php
declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator;

use ApiClients\Foundation\Resource\ResourceInterface;

trait HydrateTrait
{
    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @param string $class
     * @param array $json
     * @return ResourceInterface
     */
    protected function hydrateResource(string $class, $json): ResourceInterface
    {
        return $this->hydrator->hydrate($class, $json);
    }

    /**
     * @param Hydrator $hydrator
     */
    protected function hydratorSetter(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }
}
