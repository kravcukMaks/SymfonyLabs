<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/items', name: 'items_get', methods: ['GET'])]
    public function getItems(): JsonResponse
    {
        $items = [
            ['id' => 1, 'name' => 'Apple'],
            ['id' => 2, 'name' => 'Banana']
        ];

        return new JsonResponse($items);
    }

    #[Route('/items', name: 'items_create', methods: ['POST'])]
    public function createItem(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        return new JsonResponse([
            'message' => 'Item created',
            'data' => $data
        ]);
    }

    #[Route('/items/{id}', name: 'items_update', methods: ['PUT'])]
    public function updateItem(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        return new JsonResponse([
            'message' => "Item $id updated",
            'data' => $data
        ]);
    }

    #[Route('/items/{id}', name: 'items_delete', methods: ['DELETE'])]
    public function deleteItem(int $id): JsonResponse
    {
        return new JsonResponse([
            'message' => "Item $id deleted"
        ]);
    }
}

