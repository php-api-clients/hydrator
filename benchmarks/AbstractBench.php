<?php declare(strict_types=1);

use ApiClients\Foundation\Transport\Hydrator;
use ApiClients\Tests\Foundation\Resources\Sync\Resource;
use ApiClients\Foundation\Transport\Client;
use GeneratedHydrator\Configuration;

abstract class AbstractBench
{
    private $tmpDir = '';
    private $tmpNamespace = '';

    protected $hydrator;
    protected $hydratorNoCache;

    public function init()
    {
        $this->tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('wyrihaximus-php-api-client-tests-') . DIRECTORY_SEPARATOR;
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->tmpDir = 'C:\\t\\';
        }
        mkdir($this->tmpDir, 0777, true);
        $this->tmpNamespace = Configuration::DEFAULT_GENERATED_CLASS_NAMESPACE . uniqid('WHPACTN');

        $this->hydrator = $this->createHydrator();
        $this->hydratorNoCache = $this->createNoCacheHydrator();
    }

    protected function createHydrator(): Hydrator
    {
        return (new Hydrator(\Phake::mock(Client::class), [
            'namespace' => 'ApiClients\Tests\Foundation\Resources',
            'resource_namespace' => 'Sync',
            'resource_hydrator_cache_dir' => $this->getTmpDir(),
            'resource_hydrator_namespace' => $this->getRandomNameSpace(),
        ]));
    }

    protected function createNoCacheHydrator(): Hydrator
    {
        return (new Hydrator(\Phake::mock(Client::class), [
            'namespace' => 'ApiClients\Tests\Foundation\Resources',
            'resource_namespace' => 'Sync',
            'resource_hydrator_namespace' => $this->getRandomNameSpace(),
        ]));
    }

    public function cleanup()
    {
        $this->rmdir($this->tmpDir);
    }

    protected function getTmpDir(): string
    {
        return $this->tmpDir;
    }

    protected function getRandomNameSpace(): string
    {
        return $this->tmpNamespace;
    }

    protected function rmdir($dir)
    {
        $directory = dir($dir);
        while (false !== ($entry = $directory->read())) {
            if (in_array($entry, ['.', '..'])) {
                continue;
            }

            if (is_dir($dir . $entry)) {
                $this->rmdir($dir . $entry . DIRECTORY_SEPARATOR);
                continue;
            }

            if (is_file($dir . $entry)) {
                unlink($dir . $entry);
                continue;
            }
        }
        $directory->close();
        rmdir($dir);
    }
}