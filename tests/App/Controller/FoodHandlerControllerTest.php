<?php

namespace App\Tests\App\Controller;

use App\Controller\Api\FoodHandlerController;
use App\Service\Food\FoodCacheService;
use App\Service\Food\FoodCollection;
use App\Service\Food\FoodCollectionsBuilder;
use App\Service\Food\RequestFilterApply;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FoodHandlerControllerTest extends TestCase
{
    public function testIndexController(): void
    {
        $foodCollectionsBuilder = $this->createMock(FoodCollectionsBuilder::class);
        $foodCacheService = $this->createMock(FoodCacheService::class);

        $subject = new FoodHandlerController(
            $foodCollectionsBuilder,
            $foodCacheService,
            new RequestFilterApply(),
        );

        $collection = FoodCollection::fromArray([
            [
                'id' => 1,
                'name' => 'foo',
                'quantity' => 12000,
                'type' => 'vegetable',
                'unit' => 'g'
            ],
        ]);

        $foodCacheService->method('getVegetables')
            ->willReturn($collection);

        $request = new Request();

        $result = $subject->index($request, 'vegetable');

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals('foo', json_decode($result->getContent(), true)[0]['name']);
    }

    public function testPersistController(): void
    {
        $foodCollectionsBuilder = new FoodCollectionsBuilder();
        $foodCacheService = $this->createMock(FoodCacheService::class);

        $subject = new FoodHandlerController(
            $foodCollectionsBuilder,
            $foodCacheService,
            new RequestFilterApply(),
        );

        $request = new Request(content: file_get_contents('request.json'));

        $foodCacheService->expects($this->once())->method('saveVegetables')
            ->with($this->callback(function (FoodCollection $collection) {
                $this->assertCount(10, $collection->list());

                return true;
            }));

        $foodCacheService->expects($this->once())->method('saveFruits')
            ->with($this->callback(function (FoodCollection $collection) {
                $this->assertCount(10, $collection->list());

                return true;
            }));

        $result = $subject->persist($request);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }
}
