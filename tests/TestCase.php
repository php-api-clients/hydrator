<?php
declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Hydrator;

use ApiClients\Foundation\Hydrator\Factory;
use ApiClients\Foundation\Hydrator\Options;
use ApiClients\Tools\CommandBus\CommandBus;
use GeneratedHydrator\Configuration;
use League\Container\Container;
use League\Event\Emitter;
use League\Event\EmitterInterface;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use React\EventLoop\LoopInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $tmpDir;

    /**
     * @var string
     */
    private $tmpNamespace;

    public function setUp()
    {
        parent::setUp();
        $this->tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('wyrihaximus-php-api-client-tests-') . DIRECTORY_SEPARATOR;
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->tmpDir = 'C:\\t\\';
        }
        mkdir($this->tmpDir, 0777, true);
        $this->tmpNamespace = Configuration::DEFAULT_GENERATED_CLASS_NAMESPACE . uniqid('WHPACTN');
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->rmdir($this->tmpDir);
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

    protected function getTmpDir(): string
    {
        return $this->tmpDir;
    }

    protected function getRandomNameSpace(): string
    {
        return $this->tmpNamespace;
    }

    public function hydrate($class, $json, $namespace)
    {
        $container = new Container();
        $container->share(EmitterInterface::class, new Emitter());
        return Factory::create($container, [
            Options::NAMESPACE => 'ApiClients\Tests\Foundation\Hydrator\Resources',
            Options::NAMESPACE_SUFFIX => $namespace,
            Options::RESOURCE_CACHE_DIR => $this->getTmpDir(),
            Options::RESOURCE_NAMESPACE => $this->getRandomNameSpace(),
        ])->hydrateFQCN($class, $json);
    }

    protected function getJson()
    {
        return [
            'id' => 1,
            'slog' => 'Wyrihaximus/php-travis-client',
            'sub' => [
                'id' => 1,
                'slug' => 'Wyrihaximus/php-travis-client',
            ],
            'subs' => [
                [
                    'id' => 1,
                    'slug' => 'Wyrihaximus/php-travis-client',
                ],
                [
                    'id' => 2,
                    'slug' => 'Wyrihaximus/php-travis-client',
                ],
                [
                    'id' => 3,
                    'slug' => 'Wyrihaximus/php-travis-client',
                ],
                [],
            ],
        ];
    }

    protected function getFilesInDirectory(string $path): array
    {
        $files = [];

        $directory = new RecursiveDirectoryIterator($path);
        $directory = new RecursiveIteratorIterator($directory);

        foreach ($directory as $node) {
            if (!is_file($node->getPathname())) {
                continue;
            }

            $files[] = $node->getPathname();
        }

        return $files;
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
