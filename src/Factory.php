<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator;

use ApiClients\Foundation\Hydrator\Annotations;

class Factory
{
    const ANNOTATIONS = [
        Annotations\Collection::class => Annotations\Handler\CollectionHandler::class,
        Annotations\Nested::class => Annotations\Handler\NestedHandler::class,
        Annotations\Rename::class => Annotations\Handler\RenameHandler::class,
    ];

    public static function create(array $options = []): Hydrator
    {
        $options[Options::ANNOTATIONS] = static::annotations($options[Options::ANNOTATIONS] ?? []);

        return new Hydrator($options);
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
