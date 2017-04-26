<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Resource\ResourceInterface;
use WyriHaximus\Tactician\CommandHandler\Annotation\Handler;

/**
 * @Handler("ApiClients\Foundation\Hydrator\CommandBus\handler\BuildSyncFromAsyncHandler")
 */
class BuildSyncFromAsyncCommand
{
    /**
     * @var string
     */
    private $resource;

    /**
     * @var ResourceInterface
     */
    private $object;

    /**
     * ExtractCommand constructor.
     * @param string $resource
     * @param ResourceInterface $object
     */
    public function __construct(string $resource, ResourceInterface $object)
    {
        $this->resource = $resource;
        $this->object = $object;
    }

    /**
     * @return string
     */
    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * @return ResourceInterface
     */
    public function getObject(): ResourceInterface
    {
        return $this->object;
    }
}
