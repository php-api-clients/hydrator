<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator\Resources;

use ApiClients\Foundation\Hydrator\Annotations\Collection;
use ApiClients\Foundation\Hydrator\Annotations\Nested;
use ApiClients\Foundation\Hydrator\Annotations\Rename;
use ApiClients\Foundation\Resource\AbstractResource;
use ApiClients\Foundation\Resource\ResourceInterface;

/**
 * @Nested(sub="SubResource")
 * @Collection(subs="SubResource")
 * @Rename(slug="slog")
 */
class Resource extends AbstractResource implements ResourceInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var SubResource
     */
    protected $sub;

    /**
     * @var array
     */
    protected $subs;

    public function id() : int
    {
        return $this->id;
    }

    public function slug() : string
    {
        return $this->slug;
    }

    public function sub() : SubResource
    {
        return $this->sub;
    }

    public function subs() : array
    {
        return $this->subs;
    }

    public function refresh()
    {
    }
}
