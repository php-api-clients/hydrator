<?php
declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator;

use ApiClients\Foundation\Resource\ResourceInterface;
use function Clue\React\Block\await;

trait HydrateTrait
{
    /**
     * @return Client
     */
    abstract protected function getTransport(): Client;

    /**
     * @param string $class
     * @param array $json
     * @return ResourceInterface
     */
    protected function hydrate(string $class, array $json): ResourceInterface
    {
        return $this->getTransport()->getHydrator()->hydrate($class, $json);
    }
}
