<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\CommandBus\Handler;

use ApiClients\Foundation\Hydrator\CommandBus\Command\ExtractFQCNCommand;
use ApiClients\Foundation\Hydrator\Hydrator;

class ExtractFQCNHandler
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

    public function handle(ExtractFQCNCommand $command): array
    {
        return $this->hydrator->extract(
            $command->getClass(),
            $command->getObject()
        );
    }
}
