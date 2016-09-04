<?php
declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator;

trait HydrateTrait
{
    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @return Hydrator
     */
    protected function hydrator(): Hydrator
    {
        return $this->hydrator;
    }

    /**
     * @param Hydrator $hydrator
     */
    protected function hydratorSetter(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }
}
