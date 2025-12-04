<?php

namespace App\Controller;

use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/books')]
class BookController extends AbstractController
{
    #[Route('', name: 'book_create', methods: ['POST'])]
    public function create(Request $request, BookService $bookService): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $book = $bookService->create($data);

        return $this->json([
            'id' => $book->getId(),
            'title' => $book->getTitle(),
        ]);
    }
}
