<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\CommandBus\Handler;

use ApiClients\Foundation\Hydrator\CommandBus\Command\BuildSyncFromAsyncCommand;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Foundation\Resource\ResourceInterface;

class BuildSyncFromAsyncHandler
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

    public function handle(BuildSyncFromAsyncCommand $command): ResourceInterface
    {
        return $this->hydrator->buildSyncFromAsync(
            $command->getResource(),
            $command->getObject()
        );
    }
}
