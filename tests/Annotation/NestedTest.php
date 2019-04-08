<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\Annotations;

use ApiClients\Foundation\Hydrator\Annotation\Nested;
use ApiClients\Foundation\Resource\DummyResource;
use ApiClients\Tests\Foundation\Hydrator\TestCase;
use Doctrine\Common\Annotations\AnnotationReader;
use React\EventLoop\Factory;

/**
 * @internal
 */
class NestedTest extends TestCase
{
    public function testProperties(): void
    {
        $nested = new Nested([
            'a' => 'a',
            'b' => 'b',
            'c' => 'c',
            'd' => 'd',
            'e' => 'e',
        ]);
        $this->assertSame(
            [
                'a',
                'b',
                'c',
                'd',
                'e',
            ],
            $nested->properties()
        );
    }

    public function testHas(): void
    {
        $nested = new Nested([
            'a' => 'a',
            'b' => 'b',
            'c' => 'c',
            'd' => 'd',
            'e' => 'e',
        ]);
        $this->assertTrue($nested->has('a'));
        $this->assertTrue($nested->has('b'));
        $this->assertTrue($nested->has('c'));
        $this->assertTrue($nested->has('d'));
        $this->assertTrue($nested->has('e'));
        $this->assertFalse($nested->has('f'));
        $this->assertFalse($nested->has('g'));
        $this->assertFalse($nested->has('h'));
        $this->assertFalse($nested->has('i'));
        $this->assertFalse($nested->has('j'));
    }

    public function testGet(): void
    {
        $nested = new Nested([
            'a' => 'a',
            'b' => 'b',
            'c' => 'c',
            'd' => 'd',
            'e' => 'e',
        ]);
        $this->assertSame('a', $nested->get('a'));
        $this->assertSame('b', $nested->get('b'));
        $this->assertSame('c', $nested->get('c'));
        $this->assertSame('d', $nested->get('d'));
        $this->assertSame('e', $nested->get('e'));
    }

    public function testGetException(): void
    {
        self::expectException(\InvalidArgumentException::class);

        $nested = new Nested([]);
        $nested->get('a');
    }

    public function testDummyResourceAnnotation(): void
    {
        $loop = Factory::create();
        $dummy = new DummyResource($loop, $this->createCommandBus($loop));
        $reader = new AnnotationReader();
        $annotaion = $reader->getClassAnnotation(new \ReflectionClass($dummy), Nested::class);
        $this->assertInstanceOf(Nested::class, $annotaion);
        $this->assertSame(
            [
                'foo',
                'bar',
            ],
            $annotaion->properties()
        );
        $this->assertTrue($annotaion->has('foo'));
        $this->assertTrue($annotaion->has('bar'));
        $this->assertFalse($annotaion->has('baz'));
        $this->assertSame('Acme\Bar', $annotaion->get('foo'));
        $this->assertSame('Acme\Foo', $annotaion->get('bar'));
    }
}
