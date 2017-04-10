<?php declare(strict_types=1);

use ApiClients\Foundation\Hydrator\Factory;
use ApiClients\Foundation\Hydrator\Hydrator;
use ApiClients\Foundation\Hydrator\Options;
use ApiClients\Tools\CommandBus\CommandBus;
use ApiClients\Tools\CommandBus\CommandBusInterface;
use DI\ContainerBuilder;
use GeneratedHydrator\Configuration;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use React\EventLoop\Factory as LoopFactory;
use React\EventLoop\LoopInterface;

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
        $loop = LoopFactory::create();
        $container = ContainerBuilder::buildDevContainer();
        $container->set(LoopInterface::class, $loop);
        $container->set(CommandBusInterface::class, $this->createCommandBus($loop));
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
        $loop = LoopFactory::create();
        $container = ContainerBuilder::buildDevContainer();
        $container->set(LoopInterface::class, $loop);
        $container->set(CommandBusInterface::class, $this->createCommandBus($loop));
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

    protected function createCommandBus(LoopInterface $loop, array $map = []): CommandBus
    {
        $commandHandlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new InMemoryLocator($map),
            new HandleInflector()
        );

        return new CommandBus(
            $loop,
            $commandHandlerMiddleware
        );
    }
}