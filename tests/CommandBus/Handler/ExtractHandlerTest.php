<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\CommandBus\Command;

use function ApiClients\Foundation\get_property;
use ApiClients\Foundation\Hydrator\CommandBus\Command\ExtractCommand;
use ApiClients\Foundation\Hydrator\CommandBus\Handler\ExtractHandler;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Tests\Foundation\Hydrator\Resources\Async\SubResource;
use ApiClients\Tests\Foundation\Hydrator\TestCase;
use React\EventLoop\Factory;

/**
 * @internal
 */
class ExtractHandlerTest extends TestCase
{
    public function testHandler(): void
    {
        $loop = Factory::create();
        $result = [
            'id' => 1,
            'slug' => 'slug',
        ];
        $class = 'SubResource';
        $resource = new SubResource($loop, $this->createCommandBus($loop));
        get_property($resource, 'id')->setValue($resource, $result['id']);
        get_property($resource, 'slug')->setValue($resource, $result['slug']);

        $command = new ExtractCommand($class, $resource);
        $hydrator = $this->prophesize(Hydrator::class);
        $hydrator->extract($class, $resource)->shouldBeCalled()->willReturn($result);

        $this->assertSame($result, (new ExtractHandler($hydrator->reveal()))->handle($command));
    }
}
