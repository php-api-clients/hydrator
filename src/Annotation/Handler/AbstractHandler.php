<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\Annotation\Handler;

use ApiClients\Foundation\Hydrator\Hydrator;

abstract class AbstractHandler
{
    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * AbstractHandler constructor.
     * @param Hydrator $hydrator
     */
    public function __construct(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * @return Hydrator
     */
    protected function getHydrator(): Hydrator
    {
        return $this->hydrator;
    }
}
