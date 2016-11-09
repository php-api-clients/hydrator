<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator;

use ApiClients\Foundation\Hydrator\Annotations\EmptyResource;
use ApiClients\Foundation\Resource\EmptyResourceInterface;
use ApiClients\Tools\CommandBus\CommandBus;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\Cache;
use GeneratedHydrator\Configuration;
use Interop\Container\ContainerInterface;
use ReflectionClass;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ApiClients\Foundation\Resource\ResourceInterface;
use Zend\Hydrator\HydratorInterface;

class Hydrator
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $hydrators = [];

    /**
     * @var array
     */
    protected $annotations = [];

    /**
     * @var HandlerInterface[]
     */
    protected $annotationHandlers = [];

    /**
     * @var Reader
     */
    protected $annotationReader;

    /**
     * @param ContainerInterface $container
     * @param array $options
     */
    public function __construct(ContainerInterface $container, array $options)
    {
        $this->container = $container;
        $this->options = $options;

        $reader = new AnnotationReader();
        if (isset($this->options[Options::ANNOTATION_CACHE]) &&
            $this->options[Options::ANNOTATION_CACHE] instanceof Cache
        ) {
            $reader = new CachedReader(
                $reader,
                $this->options[Options::ANNOTATION_CACHE]
            );
        }
        $this->annotationReader = $reader;

        $this->setUpAnnotations();
    }

    protected function setUpAnnotations()
    {
        if (!isset($this->options[Options::ANNOTATIONS])) {
            return;
        }

        foreach ($this->options[Options::ANNOTATIONS] as $annotationClass => $handler) {
            $this->annotationHandlers[$annotationClass] = new $handler($this);
        }
    }

    public function preheat(string $scanTarget, string $namespace)
    {
        $directory = new RecursiveDirectoryIterator($scanTarget);
        $directory = new RecursiveIteratorIterator($directory);

        foreach ($directory as $node) {
            if (!is_file($node->getPathname())) {
                continue;
            }

            $file = substr($node->getPathname(), strlen($scanTarget));
            $file = ltrim($file, DIRECTORY_SEPARATOR);
            $file = rtrim($file, '.php');

            $class = $namespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $file);

            if (!class_exists($class)) {
                continue;
            }

            if (!is_subclass_of($class, ResourceInterface::class)) {
                continue;
            }

            $this->getHydrator($class);
            $this->annotationReader->getClassAnnotations(new ReflectionClass($class));
        }
    }

    /**
     * @param string $class
     * @param array $json
     * @return ResourceInterface
     */
    public function hydrate(string $class, array $json): ResourceInterface
    {
        $fullClassName = implode(
            '\\',
            [
                $this->options[Options::NAMESPACE],
                $this->options[Options::NAMESPACE_SUFFIX],
                $class,
            ]
        );
        return $this->hydrateFQCN($fullClassName, $json);
    }

    /**
     * @param string $class
     * @param array $json
     * @return ResourceInterface
     */
    public function hydrateFQCN(string $class, array $json): ResourceInterface
    {
        $class = $this->getEmptyOrResource($class, $json);
        $hydrator = $this->getHydrator($class);
        $object = new $class($this->container->get(CommandBus::class));
        $json = $this->hydrateApplyAnnotations($json, $object);
        $resource = $hydrator->hydrate($json, $object);
        return $resource;
    }

    /**
     * @param array $json
     * @param ResourceInterface $object
     * @return array
     */
    protected function hydrateApplyAnnotations(array $json, ResourceInterface $object): array
    {
        foreach ($this->annotationHandlers as $annotationClass => $handler) {
            $annotation = $this->getAnnotation($object, $annotationClass);
            if ($annotation === null) {
                continue;
            }

            $json = $handler->hydrate($annotation, $json, $object);
        }

        return $json;
    }

    protected function getEmptyOrResource(string $class, array $json): string
    {
        if (count($json) > 0) {
            return $class;
        }

        $annotation = $this->getAnnotation(new $class($this->container->get(CommandBus::class)), EmptyResource::class);

        if (!($annotation instanceof EmptyResource)) {
            return $class;
        }

        $emptyClass = $this->options[Options::NAMESPACE] .
            '\\' .
            $this->options[Options::NAMESPACE_SUFFIX] .
            '\\' .
            $annotation->getEmptyReplacement();

        if (!class_exists($emptyClass)) {
            return $class;
        }

        return $emptyClass;
    }

    /**
     * @param string $class
     * @param ResourceInterface $object
     * @return array
     */
    public function extract(string $class, ResourceInterface $object): array
    {
        $fullClassName = implode(
            '\\',
            [
                $this->options[Options::NAMESPACE],
                $this->options[Options::NAMESPACE_SUFFIX],
                $class,
            ]
        );
        return $this->extractFQCN($fullClassName, $object);
    }

    /**
     * Takes a fully qualified class name and extracts the data for that class from the given $object
     * @param string $class
     * @param ResourceInterface $object
     * @return array
     */
    public function extractFQCN(string $class, ResourceInterface $object): array
    {
        if ($object instanceof EmptyResourceInterface) {
            return [];
        }

        $json = $this->getHydrator($class)->extract($object);
        $json = $this->extractApplyAnnotations($object, $json);
        return $json;
    }

    /**
     * @param array $json
     * @param ResourceInterface $object
     * @return array
     */
    protected function extractApplyAnnotations(ResourceInterface $object, array $json): array
    {
        foreach ($this->annotationHandlers as $annotationClass => $handler) {
            $annotation = $this->getAnnotation($object, $annotationClass);
            if ($annotation === null) {
                continue;
            }

            $json = $handler->extract($annotation, $object, $json);
        }

        return $json;
    }

    /**
     * @param ResourceInterface $object
     * @param string $annotationClass
     * @return null|AnnotationInterface
     */
    protected function getAnnotation(ResourceInterface $object, string $annotationClass)
    {
        $class = get_class($object);
        if (isset($this->annotations[$class][$annotationClass])) {
            return $this->annotations[$class][$annotationClass];
        }

        if (!isset($this->annotations[$class])) {
            $this->annotations[$class] = [];
        }

        $this->annotations[$class][$annotationClass] = $this->annotationReader
            ->getClassAnnotation(
                new ReflectionClass($object),
                $annotationClass
            )
        ;

        if ($this->annotations[$class][$annotationClass] !== null &&
            get_class($this->annotations[$class][$annotationClass]) === $annotationClass
        ) {
            return $this->annotations[$class][$annotationClass];
        }

        $this->annotations[$class][$annotationClass] = $this->annotationReader
            ->getClassAnnotation(
                new ReflectionClass(get_parent_class($object)),
                $annotationClass
            )
        ;

        return $this->annotations[$class][$annotationClass];
    }

    /**
     * @param string $resource
     * @param ResourceInterface $object
     * @return ResourceInterface
     */
    public function buildAsyncFromSync(string $resource, ResourceInterface $object): ResourceInterface
    {
        return $this->hydrateFQCN(
            $this->options[Options::NAMESPACE] . '\\Async\\' . $resource,
            $this->extractFQCN(
                $this->options[Options::NAMESPACE] . '\\Sync\\' . $resource,
                $object
            )
        );
    }

    /**
     * @param string $class
     * @return HydratorInterface
     */
    protected function getHydrator(string $class): HydratorInterface
    {
        if (isset($this->hydrators[$class])) {
            return $this->hydrators[$class];
        }

        $config = new Configuration($class);
        if (isset($this->options[Options::RESOURCE_CACHE_DIR])) {
            $config->setGeneratedClassesTargetDir($this->options[Options::RESOURCE_CACHE_DIR]);
        }
        if (isset($this->options[Options::RESOURCE_NAMESPACE])) {
            $config->setGeneratedClassesNamespace($this->options[Options::RESOURCE_NAMESPACE]);
        }
        $hydrator = $config->createFactory()->getHydratorClass();
        $this->hydrators[$class] = new $hydrator;

        return $this->hydrators[$class];
    }
}
