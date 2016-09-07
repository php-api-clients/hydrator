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

        $hydrator = new Hydrator($options);

        self::preheat($hydrator, $options);

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

    protected static function preheat(Hydrator $hydrator, array $options)
    {
        $hasNamespaceDir = !isset($options[Options::NAMESPACE_DIR]);
        $hasNamespace = !isset($options[Options::NAMESPACE]);
        if ($hasNamespaceDir || $hasNamespace) {
            return;
        }

        $hydrator->preheat($options[Options::NAMESPACE_DIR], $options[Options::NAMESPACE]);
    }
}
