<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Hydrator\CommandBus\Command\HydrateFQCNCommand;
use ApiClients\Foundation\Hydrator\CommandBus\Handler\HydrateFQCNHandler;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Foundation\Resource\ResourceInterface;
use ApiClients\Tests\Foundation\Hydrator\TestCase;

/**
 * @internal
 */
class HydrateFQCNHandlerTest extends TestCase
{
    public function testHandler(): void
    {
        $resource = 'abc';
        $json = [];
        $result = $this->prophesize(ResourceInterface::class)->reveal();
        $command = new HydrateFQCNCommand($resource, $json);
        $hydrator = $this->prophesize(Hydrator::class);
        $hydrator->hydrateFQCN($resource, $json)->shouldBeCalled()->willReturn($result);

        $this->assertSame($result, (new HydrateFQCNHandler($hydrator->reveal()))->handle($command));
    }
}
