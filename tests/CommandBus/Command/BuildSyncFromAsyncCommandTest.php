<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Hydrator\CommandBus\Command\BuildSyncFromAsyncCommand;
use ApiClients\Foundation\Resource\ResourceInterface;
use ApiClients\Tests\Foundation\Hydrator\TestCase;

/**
 * @internal
 */
class BuildSyncFromAsyncCommandTest extends TestCase
{
    public function testCommand(): void
    {
        $resource = 'abc';
        $object = $this->prophesize(ResourceInterface::class)->reveal();
        $command = new BuildSyncFromAsyncCommand($resource, $object);
        $this->assertSame($resource, $command->getResource());
        $this->assertSame($object, $command->getObject());
    }
}
