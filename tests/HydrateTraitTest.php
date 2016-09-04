<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\Resource;

use ApiClients\Foundation\Resource\DummyResource;
use Phake;
use ApiClients\Foundation\Resource\ResourceInterface;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Tests\Foundation\Hydrator\TestCase;

class HydrateTraitTest extends TestCase
{
    public function testHydrate()
    {
        $resourceName = 'Beer';
        $resourceJson = [
            'brewery' => 'Nøgne',
            'name' => 'Dark Horizon 4th edition',
        ];

        $resource = new DummyResource();

        $hydrator = Phake::mock(Hydrator::class);

        $resource->setExtraProperties([
            'hydrator' => $hydrator,
        ]);

        Phake::when($hydrator)->hydrate($resourceName, $resourceJson)->thenReturn(Phake::mock(ResourceInterface::class));

        $resource->wrapper('hydrateResource', $resourceName, $resourceJson);

        Phake::verify($hydrator)->hydrate($resourceName, $resourceJson);
    }
}
