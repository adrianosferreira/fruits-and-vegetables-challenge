<?php

namespace App\Service\Food;

use App\Model\FoodItem;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class FoodCacheService
{
    private const CACHE_VEGETABLES = 'food.vegetables';
    private const CACHE_FRUITS     = 'food.fruits';

    public function __construct(
        private RedisAdapter $cache,
    ) {
    }

    public function saveFruits(FoodCollection $collection): void
    {
        foreach ($collection->list() as $item) {
            $this->saveItem($item, $this->cache->getItem(self::CACHE_FRUITS));
        }
    }

    public function saveVegetables(FoodCollection $collection): void
    {
        foreach ($collection->list() as $item) {
            $this->saveItem($item, $this->cache->getItem(self::CACHE_VEGETABLES));
        }
    }

    public function getVegetables(): FoodCollection
    {
        return $this->getFoods($this->cache->getItem(self::CACHE_VEGETABLES)->get() ?? []);
    }

    public function getFruits(): FoodCollection
    {
        return $this->getFoods($this->cache->getItem(self::CACHE_FRUITS)->get() ?? []);
    }

    /** @param array<mixed> $result */
    private function getFoods(array $result): FoodCollection
    {
        return FoodCollection::fromArray($result);
    }

    private function saveItem(FoodItem $item, CacheItemInterface $foodCache): void
    {
        if (!$foodCache->isHit()) {
            $foodCache->set([$item->id => $item->toArray()]);
            $this->cache->save($foodCache);

            return;
        }

        $cachedItems = $foodCache->get();
        $cachedItems[$item->id] = $item->toArray();

        $foodCache->set($cachedItems);
        $this->cache->save($foodCache);
    }
}
