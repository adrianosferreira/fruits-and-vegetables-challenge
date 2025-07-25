<?php

namespace App\Tests\App\Service;

use App\Model\FoodItem;
use App\Model\FoodType;
use App\Model\UnityType;
use App\Service\Food\FoodCollection;
use PHPUnit\Framework\TestCase;

class FoodCollectionTest extends TestCase
{
    public function testCreateCollectionFromArray(): void
    {
        $result = FoodCollection::fromArray([
            [
                'id' => 1,
                'name' => 'foo',
                'quantity' => 1,
                'type' => 'vegetable',
                'unit' => 'kg'
            ]
        ]);

        $this->assertInstanceOf(FoodCollection::class, $result);

        $foodItem = $result->list()[0];

        $this->assertInstanceOf(FoodItem::class, $foodItem);
        $this->assertEquals(1, $foodItem->id);
        $this->assertEquals('foo', $foodItem->name);
        $this->assertEquals(1000, $foodItem->quantity);
        $this->assertEquals(FoodType::VEGETABLE, $foodItem->foodType);
        $this->assertEquals(UnityType::GRAMS, $foodItem->unit);
    }

    public function testAddsItemToCollection(): void
    {
        $collection = FoodCollection::fromArray([
            [
                'id' => 1,
                'name' => 'foo',
                'quantity' => 1,
                'type' => 'vegetable',
                'unit' => 'kg'
            ],
        ]);

        $collection->add(FoodItem::fromArray([
            'id' => 2,
            'name' => 'bar',
            'quantity' => 100,
            'type' => 'vegetable',
            'unit' => 'g',
        ]));

        $list = $collection->list();

        $this->assertCount(2, $list);
        $this->assertEquals(2, $list[1]->id);
        $this->assertEquals('bar', $list[1]->name);
        $this->assertEquals(100, $list[1]->quantity);
        $this->assertEquals(FoodType::VEGETABLE, $list[1]->foodType);
    }

    public function testRemoveItem(): void
    {
        $collection = FoodCollection::fromArray([
            [
                'id' => 1,
                'name' => 'foo',
                'quantity' => 1,
                'type' => 'vegetable',
                'unit' => 'kg'
            ],
        ]);

        $collection->add(FoodItem::fromArray([
            'id' => 2,
            'name' => 'bar',
            'quantity' => 100,
            'type' => 'vegetable',
            'unit' => 'g',
        ]));

        $list = $collection->list();

        $this->assertCount(2, $list);

        $collection->remove(FoodItem::fromArray([
            'id' => 2,
            'name' => 'bar',
            'quantity' => 100,
            'type' => 'vegetable',
            'unit' => 'g',
        ]));

        $list = $collection->list();

        $this->assertCount(1, $list);
        $this->assertEquals(1, $list[0]->id);
    }

    public function testReturnGreaterThan(): void
    {
        $collection = FoodCollection::fromArray([
            [
                'id' => 1,
                'name' => 'foo',
                'quantity' => 10,
                'type' => 'vegetable',
                'unit' => 'g'
            ],
        ]);

        $collection->add(FoodItem::fromArray([
            'id' => 2,
            'name' => 'bar',
            'quantity' => 100,
            'type' => 'vegetable',
            'unit' => 'g',
        ]));

        $result = $collection->greaterThan(50);
        $this->assertCount(1, $result->list());
        $this->assertEquals(2, $result->list()[0]->id);
    }

    public function testSearch(): void
    {
        $collection = FoodCollection::fromArray([
            [
                'id' => 1,
                'name' => 'foo',
                'quantity' => 10,
                'type' => 'vegetable',
                'unit' => 'g'
            ],
        ]);

        $collection->add(FoodItem::fromArray([
            'id' => 2,
            'name' => 'bar',
            'quantity' => 100,
            'type' => 'vegetable',
            'unit' => 'g',
        ]));

        $result = $collection->search('foo');

        $this->assertCount(1, $result->list());
        $this->assertEquals(1, $result->list()[0]->id);
    }

    public function testQuantityAsKg(): void
    {
        $collection = FoodCollection::fromArray([
            [
                'id' => 1,
                'name' => 'foo',
                'quantity' => 12000,
                'type' => 'vegetable',
                'unit' => 'g'
            ],
        ]);

        $collection->add(FoodItem::fromArray([
            'id' => 2,
            'name' => 'bar',
            'quantity' => 1000,
            'type' => 'vegetable',
            'unit' => 'g',
        ]));

        $result = $collection->quantityAs(UnityType::KILOGRAMS);
        $this->assertCount(2, $result->list());
        $this->assertEquals(12, $result->list()[0]->quantity);
        $this->assertEquals(1, $result->list()[1]->quantity);
    }
}
