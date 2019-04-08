<?php
declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\Annotation;

use ApiClients\Foundation\Hydrator\AnnotationInterface;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Rename implements AnnotationInterface
{
    /**
     * @var array
     */
    protected $renameMapping = [];

    /**
     * @param array $renameMapping
     */
    public function __construct(array $renameMapping)
    {
        $this->renameMapping = $renameMapping;
    }

    /**
     * @return array
     */
    public function properties(): array
    {
        return \array_keys($this->renameMapping);
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key): bool
    {
        return isset($this->renameMapping[$key]);
    }

    /**
     * @param $key
     * @return string
     */
    public function get($key): string
    {
        if (!$this->has($key)) {
            throw new \InvalidArgumentException();
        }

        return $this->renameMapping[$key];
    }
}
