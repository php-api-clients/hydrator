<?php declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator;

use ApiClients\Foundation\Hydrator\Factory;
use ApiClients\Foundation\Hydrator\Options;
use ApiClients\Tools\CommandBus\CommandBus;
use ApiClients\Tools\TestUtilities\TestCase as BaseTestCase;
use League\Container\Container;
use League\Event\Emitter;
use League\Event\EmitterInterface;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use React\EventLoop\LoopInterface;

abstract class TestCase extends BaseTestCase
{
    public function hydrate($class, $json, $namespace)
    {
        $container = new Container();
        $container->share(EmitterInterface::class, new Emitter());

        return Factory::create($container, [
            Options::NAMESPACE => 'ApiClients\Tests\Foundation\Hydrator\Resources',
            Options::NAMESPACE_SUFFIX => $namespace,
            Options::RESOURCE_CACHE_DIR => $this->getTmpDir(),
            Options::RESOURCE_NAMESPACE => $this->getRandomNameSpace(),
        ])->hydrateFQCN($class, $json);
    }

    protected function getJson()
    {
        return [
            'id' => 1,
            'slog' => 'Wyrihaximus/php-travis-client',
            'sub' => [
                'id' => 1,
                'slug' => 'Wyrihaximus/php-travis-client',
            ],
            'subs' => [
                [
                    'id' => 1,
                    'slug' => 'Wyrihaximus/php-travis-client',
                ],
                [
                    'id' => 2,
                    'slug' => 'Wyrihaximus/php-travis-client',
                ],
                [
                    'id' => 3,
                    'slug' => 'Wyrihaximus/php-travis-client',
                ],
                [],
            ],
        ];
    }

    protected function createCommandBus(LoopInterface $loop, array $map = []): CommandBus
    {
        $commandHandlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new InMemoryLocator($map),
            new HandleInflector()
        );

        return new CommandBus(
            $loop,
            $commandHandlerMiddleware
        );
    }
}
