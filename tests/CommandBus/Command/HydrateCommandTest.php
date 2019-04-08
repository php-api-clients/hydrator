<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Hydrator\CommandBus\Command\HydrateCommand;
use ApiClients\Tests\Foundation\Hydrator\TestCase;

/**
 * @internal
 */
class HydrateCommandTest extends TestCase
{
    public function testCommand(): void
    {
        $class = 'abc';
        $json = [];
        $command = new HydrateCommand($class, $json);
        $this->assertSame($class, $command->getClass());
        $this->assertSame($json, $command->getJson());
    }
}
