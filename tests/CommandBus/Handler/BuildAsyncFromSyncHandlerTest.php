<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Hydrator\CommandBus\Command\BuildAsyncFromSyncCommand;
use ApiClients\Foundation\Hydrator\CommandBus\Handler\BuildAsyncFromSyncHandler;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Foundation\Resource\ResourceInterface;
use ApiClients\Tests\Foundation\Hydrator\TestCase;

class BuildAsyncFromSyncHandlerTest extends TestCase
{
    public function testHandler()
    {
        $resource = 'abc';
        $object = $this->prophesize(ResourceInterface::class)->reveal();
        $result = $this->prophesize(ResourceInterface::class)->reveal();
        $command = new BuildAsyncFromSyncCommand($resource, $object);
        $hydrator = $this->prophesize(Hydrator::class);
        $hydrator->buildAsyncFromSync($resource, $object)->shouldBeCalled()->willReturn($result);

        $this->assertSame($result, (new BuildAsyncFromSyncHandler($hydrator->reveal()))->handle($command));
    }
}
