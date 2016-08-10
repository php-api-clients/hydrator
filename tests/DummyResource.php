<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator;

use ApiClients\Foundation\Hydrator\Annotations\Collection;
use ApiClients\Foundation\Hydrator\Annotations\Nested;
use ApiClients\Foundation\Resource\ResourceInterface;

/**
 * @Nested(foo="Acme\Bar", bar="Acme\Foo")
 * @Collection(foo="Acme\Bar", bar="Acme\Foo")
 */
class DummyResource implements ResourceInterface
{
    public function refresh()
    {
        // void
    }
}
