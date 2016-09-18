<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\CommandBus\Handler;

use ApiClients\Foundation\Hydrator\CommandBus\Command\ExtractCommand;
use ApiClients\Foundation\Hydrator\Hydrator;

class ExtractHandler
{
    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * ExtractHandler constructor.
     * @param Hydrator $hydrator
     */
    public function __construct(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function handle(ExtractCommand $command): array
    {
        return $this->hydrator->extract(
            $command->getClass(),
            $command->getObject()
        );
    }
}
