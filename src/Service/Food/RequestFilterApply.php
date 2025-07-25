<?php

namespace App\Service\Food;

use App\Model\UnityType;
use Symfony\Component\HttpFoundation\Request;

class RequestFilterApply
{
    public function apply(Request $request, FoodCollection $collection): FoodCollection
    {
        if ($request->query->get('quantity_greater_than')) {
            $collection = $collection->greaterThan((float) $request->query->get('quantity_greater_than'));
        }

        if ($request->query->get('search')) {
            $collection = $collection->search((string) $request->query->get('search'));
        }

        if ($request->query->get('quantity_as')) {
            $collection = $collection->quantityAs(UnityType::from((string) $request->query->get('quantity_as')));
        }

        return $collection;
    }
}
