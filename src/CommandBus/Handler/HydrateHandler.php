<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\CommandBus\Handler;

use ApiClients\Foundation\Hydrator\CommandBus\Command\HydrateCommand;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Foundation\Resource\ResourceInterface;

class HydrateHandler
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

    public function handle(HydrateCommand $command): ResourceInterface
    {
        return $this->hydrator->hydrate(
            $command->getClass(),
            $command->getJson()
        );
    }
}
