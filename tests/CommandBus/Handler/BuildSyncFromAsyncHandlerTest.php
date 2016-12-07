<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Hydrator\CommandBus\Command\BuildSyncFromAsyncCommand;
use ApiClients\Foundation\Hydrator\CommandBus\Handler\BuildSyncFromAsyncHandler;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Foundation\Resource\ResourceInterface;
use ApiClients\Tests\Foundation\Hydrator\TestCase;

class BuildSyncFromAsyncHandlerTest extends TestCase
{
    public function testHandler()
    {
        $resource = 'abc';
        $object = $this->prophesize(ResourceInterface::class)->reveal();
        $result = $this->prophesize(ResourceInterface::class)->reveal();
        $command = new BuildSyncFromAsyncCommand($resource, $object);
        $hydrator = $this->prophesize(Hydrator::class);
        $hydrator->buildSyncFromAsync($resource, $object)->shouldBeCalled()->willReturn($result);

        $this->assertSame($result, (new BuildSyncFromAsyncHandler($hydrator->reveal()))->handle($command));
    }
}
