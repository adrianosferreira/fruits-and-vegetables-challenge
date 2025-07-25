<?php

declare(strict_types=1);

namespace App\Model;

use Webmozart\Assert\Assert;

class FoodItem
{
    private function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly FoodType $foodType,
        public float $quantity,
        public UnityType $unit,
    ) {
    }

    /** @param array<mixed> $data */
    public static function fromArray(array $data): FoodItem
    {
        Assert::positiveInteger($data['id'] ?? null);
        Assert::stringNotEmpty($data['name'] ?? null);
        assert::numeric($data['quantity'] ?? null);

        return new FoodItem(
            $data['id'],
            $data['name'],
            FoodType::from($data['type']),
            self::normalizeQuantity((float) $data['quantity'], UnityType::from($data['unit'])),
            UnityType::GRAMS,
        );
    }

    /** @return array<mixed>  */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->foodType->value,
            'quantity' => $this->quantity,
            'unit' => $this->unit->value,
        ];
    }

    private static function normalizeQuantity(float $quantity, UnityType $type): float
    {
        return $type === UnityType::KILOGRAMS ? $quantity * 1000 : $quantity;
    }
}
