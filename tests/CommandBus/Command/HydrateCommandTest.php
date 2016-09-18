<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Hydrator\CommandBus\Command\HydrateCommand;
use ApiClients\Tests\Foundation\Hydrator\TestCase;

class HydrateCommandTest extends TestCase
{
    public function testCommand()
    {
        $class = 'abc';
        $json = [];
        $command = new HydrateCommand($class, $json);
        $this->assertSame($class, $command->getClass());
        $this->assertSame($json, $command->getJson());
    }
}
