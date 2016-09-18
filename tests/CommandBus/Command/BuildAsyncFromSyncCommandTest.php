<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Hydrator\CommandBus\Command\BuildAsyncFromSyncCommand;
use ApiClients\Foundation\Resource\ResourceInterface;
use ApiClients\Tests\Foundation\Hydrator\TestCase;

class BuildAsyncFromSyncCommandTest extends TestCase
{
    public function testCommand()
    {
        $resource = 'abc';
        $object = $this->prophesize(ResourceInterface::class)->reveal();
        $command = new BuildAsyncFromSyncCommand($resource, $object);
        $this->assertSame($resource, $command->getResource());
        $this->assertSame($object, $command->getObject());
    }
}
