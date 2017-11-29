<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\CommandBus\Command;

use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;

/**
 * @Handler("ApiClients\Foundation\Hydrator\CommandBus\Handler\HydrateHandler")
 */
class HydrateCommand
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var array
     */
    private $json;

    /**
     * HydrateCommand constructor.
     * @param string $class
     * @param array $json
     */
    public function __construct(string $class, array $json)
    {
        $this->class = $class;
        $this->json = $json;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return array
     */
    public function getJson(): array
    {
        return $this->json;
    }
}
