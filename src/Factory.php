<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator;

use ApiClients\Tools\CommandBus\CommandBusInterface;
use React\EventLoop\LoopInterface;

class Factory
{
    const ANNOTATIONS = [
        Annotation\Collection::class => Annotation\Handler\CollectionHandler::class,
        Annotation\Nested::class => Annotation\Handler\NestedHandler::class,
        Annotation\Rename::class => Annotation\Handler\RenameHandler::class,
    ];

    public static function create(LoopInterface $loop, CommandBusInterface $commandBus, array $options): Hydrator
    {
        $options[Options::ANNOTATIONS] = static::annotations($options[Options::ANNOTATIONS] ?? []);

        $hydrator = new Hydrator($loop, $commandBus, $options);

        return $hydrator;
    }

    protected static function annotations(array $annotations): array
    {
        foreach (static::ANNOTATIONS as $annotation => $handler) {
            if (isset($annotations[$annotation])) {
                continue;
            }

            $annotations[$annotation] = $handler;
        }

        return $annotations;
    }
}
