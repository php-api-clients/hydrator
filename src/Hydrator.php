<?php declare(strict_types=1);

namespace ApiClients\Foundation\Hydrator;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\Cache;
use GeneratedHydrator\Configuration;
use ReflectionClass;
use ApiClients\Foundation\Resource\ResourceInterface;
use ApiClients\Foundation\Resource\AbstractResource;
use Zend\Hydrator\HydratorInterface;

class Hydrator
{
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
     * @param array $options
     */
    public function __construct(array $options)
    {
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
        $this->addSelfToExtraProperties();
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

    protected function addSelfToExtraProperties()
    {
        $this->options[Options::EXTRA_PROPERTIES]['hydrator'] = $this;
    }

    public function preheat()
    {
        // TODO
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
        $hydrator = $this->getHydrator($class);
        $object = new $class();
        $json = $this->hydrateApplyAnnotations($json, $object);
        $resource = $hydrator->hydrate($json, $object);
        if ($resource instanceof AbstractResource) {
            $resource->setExtraProperties($this->options[Options::EXTRA_PROPERTIES]);
        }
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

        if (get_class($this->annotations[$class][$annotationClass]) === $annotationClass) {
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
