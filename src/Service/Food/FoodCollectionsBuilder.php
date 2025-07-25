<?php

namespace App\Service\Food;

use App\Model\FoodItem;
use App\Model\FoodType;

class FoodCollectionsBuilder
{
    private FoodCollection $fruits;
    private FoodCollection $vegetables;

    public function fromJson(string $content): self
    {
        $items = json_decode($content, true, flags: JSON_THROW_ON_ERROR);

        $this->vegetables = new FoodCollection();
        $this->fruits     = new FoodCollection();

        foreach ($items ?? [] as $item) {
            $foodItem = FoodItem::fromArray($item);

            if ($foodItem->foodType === FoodType::VEGETABLE) {
                $this->vegetables->add($foodItem);

                continue;
            }

            $this->fruits->add($foodItem);
        }

        return $this;
    }

    public function getFruits(): FoodCollection
    {
        return $this->fruits;
    }

    public function getVegetables(): FoodCollection
    {
        return $this->vegetables;
    }
}
