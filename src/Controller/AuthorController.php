<?php

namespace App\Controller;

use App\Service\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/authors')]
class AuthorController extends AbstractController
{
    #[Route('', name: 'author_create', methods: ['POST'])]
    public function create(Request $request, AuthorService $service): Response
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $author = $service->create($data);

        return $this->json([
            'id' => $author->getId(),
            'name' => $author->getName()
        ], Response::HTTP_CREATED);
    }
}
