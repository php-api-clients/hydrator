<?php declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator;

use ApiClients\Foundation\Hydrator\Factory;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Foundation\Hydrator\Options;
use DI\ContainerBuilder;

class FactoryTest extends TestCase
{
    public function testCreate()
    {
        $container = ContainerBuilder::buildDevContainer();
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
}
