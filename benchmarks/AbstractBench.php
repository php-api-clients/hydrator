<?php declare(strict_types=1);

use ApiClients\Foundation\Hydrator\Factory;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Foundation\Hydrator\Options;
use GeneratedHydrator\Configuration;
use League\Container\Container;
use League\Event\Emitter;
use League\Event\EmitterInterface;
use League\Tactician\CommandBus;
use League\Tactician\Setup\QuickStart;

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
        $container = new Container();
        $container->share(EmitterInterface::class, new Emitter());
        $container->share(CommandBus::class, QuickStart::create([]));
        return Factory::create(
            $container,
            [
                Options::NAMESPACE => 'ApiClients\Tests\Foundation\Hydrator\Resources',
                Options::NAMESPACE_SUFFIX => 'Sync',
                Options::RESOURCE_CACHE_DIR => $this->getTmpDir(),
                Options::RESOURCE_NAMESPACE => $this->getRandomNameSpace(),
            ]
        );
    }

    protected function createNoCacheHydrator(): Hydrator
    {
        $container = new Container();
        $container->share(EmitterInterface::class, new Emitter());
        $container->share(CommandBus::class, QuickStart::create([]));
        return Factory::create(
            $container,
            [
                Options::NAMESPACE => 'ApiClients\Tests\Foundation\Hydrator\Resources',
                Options::NAMESPACE_SUFFIX => 'Sync',
                Options::RESOURCE_NAMESPACE => $this->getRandomNameSpace(),
            ]
        );
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