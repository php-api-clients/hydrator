<?php declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator;

use ApiClients\Foundation\Hydrator\Factory;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Foundation\Hydrator\Options;
use React\EventLoop\Factory as LoopFactory;

/**
 * @internal
 */
class FactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $loop = LoopFactory::create();
        $commandBus = $this->createCommandBus($loop);
        $hydrator = Factory::create(
            $loop,
            $commandBus,
            [
                Options::NAMESPACE => 'ApiClients\Tests\Foundation\Hydrator\Resources',
                Options::NAMESPACE_SUFFIX => 'Async',
                Options::RESOURCE_CACHE_DIR => $this->getTmpDir(),
                Options::RESOURCE_NAMESPACE => $this->getRandomNameSpace(),
            ]
        );

        $this->assertInstanceOf(Hydrator::class, $hydrator);
    }
}
