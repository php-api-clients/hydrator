<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\CommandBus\Handler;

use ApiClients\Foundation\Hydrator\CommandBus\Command\HydrateFQCNCommand;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Foundation\Resource\ResourceInterface;

class HydrateFQCNHandler
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

    public function handle(HydrateFQCNCommand $command): ResourceInterface
    {
        return $this->hydrator->hydrateFQCN(
            $command->getClass(),
            $command->getJson()
        );
    }
}
