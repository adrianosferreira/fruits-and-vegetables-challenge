<?php

namespace App\Tests\App\Service;

use App\Model\FoodType;
use App\Model\UnityType;
use App\Service\Food\FoodCollectionsBuilder;
use PHPUnit\Framework\TestCase;

class FoodCollectionsBuilderTest extends TestCase
{
    public function testBuildsFromJson(): void
    {
        $json = file_get_contents('request.json');
        $subject = new FoodCollectionsBuilder();
        $subject->fromJson($json);

        $this->assertCount(10, $subject->getVegetables()->list());
        $this->assertCount(10, $subject->getFruits()->list());

        $this->assertEquals(FoodType::VEGETABLE, $subject->getVegetables()->list()[0]->foodType);
        $this->assertEquals(FoodType::VEGETABLE, $subject->getVegetables()->list()[1]->foodType);
        $this->assertEquals(FoodType::VEGETABLE, $subject->getVegetables()->list()[2]->foodType);
        $this->assertEquals(FoodType::VEGETABLE, $subject->getVegetables()->list()[3]->foodType);
        $this->assertEquals(FoodType::VEGETABLE, $subject->getVegetables()->list()[4]->foodType);
        $this->assertEquals(FoodType::VEGETABLE, $subject->getVegetables()->list()[5]->foodType);
        $this->assertEquals(FoodType::VEGETABLE, $subject->getVegetables()->list()[6]->foodType);
        $this->assertEquals(FoodType::VEGETABLE, $subject->getVegetables()->list()[7]->foodType);
        $this->assertEquals(FoodType::VEGETABLE, $subject->getVegetables()->list()[8]->foodType);
        $this->assertEquals(FoodType::VEGETABLE, $subject->getVegetables()->list()[9]->foodType);

        $this->assertEquals(FoodType::FRUIT, $subject->getFruits()->list()[0]->foodType);
        $this->assertEquals(FoodType::FRUIT, $subject->getFruits()->list()[1]->foodType);
        $this->assertEquals(FoodType::FRUIT, $subject->getFruits()->list()[2]->foodType);
        $this->assertEquals(FoodType::FRUIT, $subject->getFruits()->list()[3]->foodType);
        $this->assertEquals(FoodType::FRUIT, $subject->getFruits()->list()[4]->foodType);
        $this->assertEquals(FoodType::FRUIT, $subject->getFruits()->list()[5]->foodType);
        $this->assertEquals(FoodType::FRUIT, $subject->getFruits()->list()[6]->foodType);
        $this->assertEquals(FoodType::FRUIT, $subject->getFruits()->list()[7]->foodType);
        $this->assertEquals(FoodType::FRUIT, $subject->getFruits()->list()[8]->foodType);
        $this->assertEquals(FoodType::FRUIT, $subject->getFruits()->list()[9]->foodType);

        $this->assertEquals(1, $subject->getVegetables()->list()[0]->id);
        $this->assertEquals('Carrot', $subject->getVegetables()->list()[0]->name);
        $this->assertEquals(FoodType::VEGETABLE, $subject->getVegetables()->list()[0]->foodType);
        $this->assertEquals(10922, $subject->getVegetables()->list()[0]->quantity);
        $this->assertEquals(UnityType::GRAMS, $subject->getVegetables()->list()[0]->unit);

        $this->assertEquals(2, $subject->getFruits()->list()[0]->id);
        $this->assertEquals('Apples', $subject->getFruits()->list()[0]->name);
        $this->assertEquals(FoodType::FRUIT, $subject->getFruits()->list()[0]->foodType);
        $this->assertEquals(20000, $subject->getFruits()->list()[0]->quantity);
        $this->assertEquals(UnityType::GRAMS, $subject->getFruits()->list()[0]->unit);
    }
}
