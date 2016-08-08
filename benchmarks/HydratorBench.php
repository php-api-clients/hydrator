<?php declare(strict_types=1);

use ApiClients\Foundation\Transport\Hydrator;
use ApiClients\Tests\Foundation\Resources\Sync\Resource;
use PhpBench\Benchmark\Metadata\Annotations\ParamProviders;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;

/**
 * @BeforeMethods({"init"})
 * @AfterMethods({"cleanup"})
 */
class HydratorBench extends AbstractBench
{
    public function provideObjects()
    {
        return [
            'complicated' => [
                'resource' => Resource::class,
                'json' => [
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
                    ],
                ],
            ],
        ];
    }
    
    /**
     * @Iterations(10)
     * @Revs(1000)
     * @ParamProviders({"provideObjects"})
     */
    public function benchPreHeatedHydrator(array $params)
    {
        return $this->hydrator->hydrateFQCN($params['resource'], $params['json']);
    }

    /**
     * @Iterations(10)
     * @Revs(1000)
     * @ParamProviders({"provideObjects"})
     */
    public function benchPreHeatedNoCacheHydrator(array $params)
    {
        return $this->hydratorNoCache->hydrateFQCN($params['resource'], $params['json']);
    }

    /**
     * @Warmup(10)
     * @Iterations(10)
     * @Revs(1000)
     * @ParamProviders({"provideObjects"})
     */
    public function benchFreshCachedHydrator(array $params)
    {
        return $this->createHydrator()->hydrateFQCN($params['resource'], $params['json']);
    }

    /**
     * @Iterations(10)
     * @Revs(1000)
     * @ParamProviders({"provideObjects"})
     */
    public function benchFreshNoCacheHydrator(array $params)
    {
        return $this->createNoCacheHydrator()->hydrateFQCN($params['resource'], $params['json']);
    }
}