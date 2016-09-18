<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Hydrator\CommandBus\Command\ExtractFQCNCommand;
use ApiClients\Foundation\Resource\ResourceInterface;
use ApiClients\Tests\Foundation\Hydrator\TestCase;

class ExtractFQCNCommandTest extends TestCase
{
    public function testCommand()
    {
        $class = 'abc';
        $object = $this->prophesize(ResourceInterface::class)->reveal();
        $command = new ExtractFQCNCommand($class, $object);
        $this->assertSame($class, $command->getClass());
        $this->assertSame($object, $command->getObject());
    }
}
