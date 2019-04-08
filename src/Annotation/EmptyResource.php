<?php
declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator\Annotation;

use ApiClients\Foundation\Hydrator\AnnotationInterface;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class EmptyResource implements AnnotationInterface
{
    /**
     * @var string
     */
    protected $emptyReplacement;

    /**
     * Nested constructor.
     * @param array $emptyReplacements
     */
    public function __construct(array $emptyReplacements)
    {
        $this->emptyReplacement = \current($emptyReplacements);
    }

    /**
     * @return string
     */
    public function getEmptyReplacement()
    {
        return $this->emptyReplacement;
    }
}
