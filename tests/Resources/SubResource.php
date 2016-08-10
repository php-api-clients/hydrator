<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\Resources;

use ApiClients\Foundation\Resource\ResourceInterface;

class SubResource implements ResourceInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $slug;

    public function id() : int
    {
        return $this->id;
    }

    public function slug() : string
    {
        return $this->slug;
    }

    public function refresh()
    {
    }
}
