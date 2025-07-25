<?php

namespace App\Tests\App\Service;

use App\Model\FoodItem;
use App\Service\Food\FoodCollection;
use App\Service\Food\RequestFilterApply;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RequestFilterApplyTest extends TestCase
{
    public function testApplyQuantityGreaterThan(): void
    {
        $subject = new RequestFilterApply();

        $request = new Request();
        $request->query->add(['quantity_greater_than' => 1000]);

        $foodCollection = FoodCollection::fromArray([
            [
                'id' => 1,
                'name' => 'foo',
                'quantity' => 12000,
                'type' => 'vegetable',
                'unit' => 'g'
            ],
        ]);

        $foodCollection->add(FoodItem::fromArray([
            'id' => 2,
            'name' => 'bar',
            'quantity' => 1000,
            'type' => 'vegetable',
            'unit' => 'g',
        ]));

        $result = $subject->apply($request, $foodCollection);
        $this->assertCount(1, $result->list());
        $this->assertEquals(12000, $result->list()[0]->quantity);
    }

    public function testApplySearch(): void
    {
        $subject = new RequestFilterApply();

        $request = new Request();
        $request->query->add(['search' => 'bar']);

        $foodCollection = FoodCollection::fromArray([
            [
                'id' => 1,
                'name' => 'foo',
                'quantity' => 12000,
                'type' => 'vegetable',
                'unit' => 'g'
            ],
        ]);

        $foodCollection->add(FoodItem::fromArray([
            'id' => 2,
            'name' => 'bar',
            'quantity' => 1000,
            'type' => 'vegetable',
            'unit' => 'g',
        ]));

        $result = $subject->apply($request, $foodCollection);
        $this->assertCount(1, $result->list());
        $this->assertEquals(1000, $result->list()[0]->quantity);
    }

    public function testApplyQuantityAsKg(): void
    {
        $subject = new RequestFilterApply();

        $request = new Request();
        $request->query->add(['quantity_as' => 'kg']);

        $foodCollection = FoodCollection::fromArray([
            [
                'id' => 1,
                'name' => 'foo',
                'quantity' => 12000,
                'type' => 'vegetable',
                'unit' => 'g'
            ],
        ]);

        $foodCollection->add(FoodItem::fromArray([
            'id' => 2,
            'name' => 'bar',
            'quantity' => 1000,
            'type' => 'vegetable',
            'unit' => 'g',
        ]));

        $result = $subject->apply($request, $foodCollection);
        $this->assertCount(2, $result->list());
        $this->assertEquals(12, $result->list()[0]->quantity);
    }
}
