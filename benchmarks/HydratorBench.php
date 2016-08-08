<?php declare(strict_types=1);

use ApiClients\Foundation\Transport\Hydrator;
use ApiClients\Tests\Foundation\Resources\Sync\Resource;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use PhpBench\Benchmark\Metadata\Annotations\Warmup;

/**
 * @BeforeMethods({"init"})
 * @AfterMethods({"cleanup"})
 */
class HydratorBench extends AbstractBench
{
    /**
     * @Iterations(10)
     * @Revs(1000)
     */
    public function benchPreHeatedHydrator()
    {
        return $this->hydrator->hydrateFQCN(Resource::class, [
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
        ]);
    }

    /**
     * @Iterations(10)
     * @Revs(1000)
     */
    public function benchPreHeatedNoCacheHydrator()
    {
        return $this->hydratorNoCache->hydrateFQCN(Resource::class, [
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
        ]);
    }

    /**
     * @Warmup(10)
     * @Iterations(10)
     * @Revs(1000)
     */
    public function benchFreshCachedHydrator()
    {
        return $this->createHydrator()->hydrateFQCN(Resource::class, [
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
        ]);
    }

    /**
     * @Iterations(10)
     * @Revs(1000)
     */
    public function benchFreshNoCacheHydrator()
    {
        return $this->createNoCacheHydrator()->hydrateFQCN(Resource::class, [
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
        ]);
    }
}