<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Hydrator\CommandBus\Command\HydrateCommand;
use ApiClients\Foundation\Hydrator\CommandBus\Handler\HydrateHandler;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Foundation\Resource\ResourceInterface;
use ApiClients\Tests\Foundation\Hydrator\TestCase;

class HydrateHandlerTest extends TestCase
{
    public function testHandler()
    {
        $resource = 'abc';
        $json = [];
        $result = $this->prophesize(ResourceInterface::class)->reveal();
        $command = new HydrateCommand($resource, $json);
        $hydrator = $this->prophesize(Hydrator::class);
        $hydrator->hydrate($resource, $json)->shouldBeCalled()->willReturn($result);

        $this->assertSame($result, (new HydrateHandler($hydrator->reveal()))->handle($command));
    }
}
