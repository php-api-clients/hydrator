<?php declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator;

use ApiClients\Foundation\Events\CommandLocatorEvent;
use ApiClients\Foundation\Hydrator\Factory;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Foundation\Hydrator\Options;
use League\Container\Container;
use League\Event\Emitter;
use League\Event\EmitterInterface;

class FactoryTest extends TestCase
{
    public function testCreate()
    {
        $container = new Container();
        $container->share(EmitterInterface::class, new Emitter());
        $hydrator = Factory::create(
            $container,
            [
                Options::NAMESPACE => 'ApiClients\Tests\Foundation\Hydrator\Resources',
                Options::NAMESPACE_SUFFIX => 'Async',
                Options::RESOURCE_CACHE_DIR => $this->getTmpDir(),
                Options::RESOURCE_NAMESPACE => $this->getRandomNameSpace(),
            ]
        );

        $this->assertInstanceOf(Hydrator::class, $hydrator);
    }

    public function testCommandBusEvent()
    {
        $emitter = new Emitter();
        $container = new Container();
        $container->share(EmitterInterface::class, $emitter);
        Factory::create(
            $container,
            [
                Options::NAMESPACE => 'ApiClients\Tests\Foundation\Hydrator\Resources',
                Options::NAMESPACE_SUFFIX => 'Async',
                Options::RESOURCE_CACHE_DIR => $this->getTmpDir(),
                Options::RESOURCE_NAMESPACE => $this->getRandomNameSpace(),
            ]
        );

        $event = CommandLocatorEvent::create();
        $this->assertSame(0, count($event->getMap()));
        $emitter->emit($event);
        $this->assertSame(5, count($event->getMap()));
    }
}
