<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator;

use Exception;

class OutsideNamespaceException extends Exception
{
    public static function create(string $class, string $namepace): OutsideNamespaceException
    {
        return new self('Class "' . $class . '" isn\'t in our configured namespace "' . $namepace . '"');
    }
}
