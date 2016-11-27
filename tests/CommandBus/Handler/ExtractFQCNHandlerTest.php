<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\CommandBus\Command;

use ApiClients\Foundation\Hydrator\CommandBus\Command\ExtractFQCNCommand;
use ApiClients\Foundation\Hydrator\CommandBus\Handler\ExtractFQCNHandler;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Tests\Foundation\Hydrator\Resources\Async\SubResource;
use ApiClients\Tests\Foundation\Hydrator\TestCase;
use function ApiClients\Foundation\get_property;
use React\EventLoop\Factory;

class ExtractFQCNHandlerTest extends TestCase
{
    public function testHandler()
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

        $command = new ExtractFQCNCommand($class, $resource);
        $hydrator = $this->prophesize(Hydrator::class);
        $hydrator->extractFQCN($class, $resource)->shouldBeCalled()->willReturn($result);

        $this->assertSame($result, (new ExtractFQCNHandler($hydrator->reveal()))->handle($command));
    }
}
