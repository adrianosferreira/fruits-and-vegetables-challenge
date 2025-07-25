<?php

namespace App\Service\Food;

use App\Model\FoodItem;
use App\Model\UnityType;

class FoodCollection
{
    /** @var array<FoodItem> */
    private array $items = [];

    /** @param array<mixed> $data */
    public static function fromArray(array $data): FoodCollection
    {
        $collection = new FoodCollection();

        foreach ($data as $item) {
            $collection->add(FoodItem::fromArray($item));
        }

        return $collection;
    }

    public function add(FoodItem $item): void
    {
        $this->items[] = $item;
    }

    public function remove(FoodItem $itemToRemove): void
    {
        $this->items = array_filter(
            $this->items,
            static fn(FoodItem $item) => $item->id !== $itemToRemove->id,
        );
    }

    public function greaterThan(float $quantity): self
    {
        return $this->newCollectionFrom(array_filter(
            $this->items,
            fn(FoodItem $item) => $item->quantity > $quantity
        ),);
    }

    public function search(string $name): self
    {
        return $this->newCollectionFrom(array_filter(
            $this->items,
            fn(FoodItem $item) => str_contains($item->name, $name)
        ),);
    }

    public function quantityAs(UnityType $type): self
    {
        return $this->newCollectionFrom(
            array_map(function ($item) use ($type) {
                if ($type === UnityType::KILOGRAMS) {
                    $item->quantity = $item->quantity / 1000;
                    $item->unit = UnityType::KILOGRAMS;
                }

                return $item;
            }, $this->items),
        );
    }

    /** @return array<FoodItem> */
    public function list(): array
    {
        return $this->items;
    }

    /** @param array<FoodItem> $filtered */
    private function newCollectionFrom(array $filtered): self
    {
        $collection = new self();

        foreach ($filtered as $item) {
            $collection->add($item);
        }

        return $collection;
    }
}
