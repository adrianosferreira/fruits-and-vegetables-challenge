<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Model\FoodType;
use App\Service\Food\FoodCacheService;
use App\Service\Food\FoodCollectionsBuilder;
use App\Service\Food\RequestFilterApply;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class FoodHandlerController extends AbstractController
{
    public function __construct(
        private readonly FoodCollectionsBuilder $foodCollectionsBuilder,
        private readonly FoodCacheService $foodCacheService,
        private readonly RequestFilterApply $requestFilterApply
    ) {
    }

    #[Route('/api/food/{type}', name: 'food')]
    public function index(
        Request $request,
        string $type,
    ): Response {
        $type = FoodType::tryFrom($type);

        if ($type === null) {
            return new JsonResponse(['error' => 'invalid food type'], Response::HTTP_BAD_REQUEST);
        }

        $collectionFetch = match ($type) {
            FoodType::VEGETABLE => $this->foodCacheService->getVegetables(...),
            FoodType::FRUIT => $this->foodCacheService->getFruits(...),
        };

        try {
            $collection = $this->requestFilterApply->apply($request, $collectionFetch());
        } catch (Throwable $e) {
            return new JsonResponse(
                ['error' => 'error when applying request filters: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse($collection->list(), Response::HTTP_OK);
    }

    #[Route('/api/food', name: 'food_persist', methods: ['POST'])]
    public function persist(Request $request): Response
    {
        try {
            $collections = $this->foodCollectionsBuilder->fromJson((string) $request->getContent());

            $this->foodCacheService->saveFruits($collections->getFruits());
            $this->foodCacheService->saveVegetables($collections->getVegetables());
        } catch (Throwable $e) {
            var_dump($e->getMessage());
            return new JsonResponse(
                ['error' => 'error when persisting new items: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(['success' => true], Response::HTTP_OK);
    }
}
