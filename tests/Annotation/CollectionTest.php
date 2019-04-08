<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\Annotations;

use ApiClients\Foundation\Hydrator\Annotation\Collection;
use ApiClients\Foundation\Resource\DummyResource;
use ApiClients\Tests\Foundation\Hydrator\TestCase;
use Doctrine\Common\Annotations\AnnotationReader;
use React\EventLoop\Factory;

/**
 * @internal
 */
class CollectionTest extends TestCase
{
    public function testProperties(): void
    {
        $collection = new Collection([
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
            $collection->properties()
        );
    }

    public function testHas(): void
    {
        $collection = new Collection([
            'a' => 'a',
            'b' => 'b',
            'c' => 'c',
            'd' => 'd',
            'e' => 'e',
        ]);
        $this->assertTrue($collection->has('a'));
        $this->assertTrue($collection->has('b'));
        $this->assertTrue($collection->has('c'));
        $this->assertTrue($collection->has('d'));
        $this->assertTrue($collection->has('e'));
        $this->assertFalse($collection->has('f'));
        $this->assertFalse($collection->has('g'));
        $this->assertFalse($collection->has('h'));
        $this->assertFalse($collection->has('i'));
        $this->assertFalse($collection->has('j'));
    }

    public function testGet(): void
    {
        $collection = new Collection([
            'a' => 'a',
            'b' => 'b',
            'c' => 'c',
            'd' => 'd',
            'e' => 'e',
        ]);
        $this->assertSame('a', $collection->get('a'));
        $this->assertSame('b', $collection->get('b'));
        $this->assertSame('c', $collection->get('c'));
        $this->assertSame('d', $collection->get('d'));
        $this->assertSame('e', $collection->get('e'));
    }

    public function testGetException(): void
    {
        self::expectException(\InvalidArgumentException::class);

        $collection = new Collection([]);
        $collection->get('a');
    }

    public function testDummyResourceAnnotation(): void
    {
        $loop = Factory::create();
        $dummy = new DummyResource($loop, $this->createCommandBus($loop));
        $reader = new AnnotationReader();
        $annotaion = $reader->getClassAnnotation(new \ReflectionClass($dummy), Collection::class);
        $this->assertInstanceOf(Collection::class, $annotaion);
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
