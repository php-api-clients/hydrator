<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\Resources;

use ApiClients\Foundation\Resource\EmptyResourceInterface;

class EmptySubResource implements EmptyResourceInterface
{
    public function id() : int
    {
        return null;
    }

    public function slug() : string
    {
        return null;
    }

    public function refresh()
    {
    }
}
