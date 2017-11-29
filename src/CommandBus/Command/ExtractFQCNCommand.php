<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Resource\ResourceInterface;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;

/**
 * @Handler("ApiClients\Foundation\Hydrator\CommandBus\handler\ExtractFQCNHandler")
 */
class ExtractFQCNCommand
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var ResourceInterface
     */
    private $object;

    /**
     * ExtractCommand constructor.
     * @param string            $class
     * @param ResourceInterface $object
     */
    public function __construct(string $class, ResourceInterface $object)
    {
        $this->class = $class;
        $this->object = $object;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return ResourceInterface
     */
    public function getObject(): ResourceInterface
    {
        return $this->object;
    }
}
