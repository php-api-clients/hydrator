<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Hydrator\CommandBus\Command\ExtractCommand;
use ApiClients\Foundation\Resource\ResourceInterface;
use ApiClients\Tests\Foundation\Hydrator\TestCase;

class ExtractCommandTest extends TestCase
{
    public function testCommand()
    {
        $class = 'abc';
        $object = $this->prophesize(ResourceInterface::class)->reveal();
        $command = new ExtractCommand($class, $object);
        $this->assertSame($class, $command->getClass());
        $this->assertSame($object, $command->getObject());
    }
}
