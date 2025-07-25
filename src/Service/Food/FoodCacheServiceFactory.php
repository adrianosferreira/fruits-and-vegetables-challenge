<?php

declare(strict_types=1);

namespace App\Service\Food;

use Symfony\Component\Cache\Adapter\RedisAdapter;

class FoodCacheServiceFactory
{
    public function __invoke(): FoodCacheService
    {
        return new FoodCacheService(
            new RedisAdapter(
                RedisAdapter::createConnection(
                    'redis://redis:6379'
                ),
            ),
        );
    }
}
