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
    public function create(
    Request $request,
    AuthorService $service,
    \App\Service\RequestCheckerService $checker
    ): Response {
    
    $data = $checker->check($request, ['name']);

    $author = $service->create($data);

    return $this->json([
        'id' => $author->getId(),
        'name' => $author->getName()
    ], Response::HTTP_CREATED);
 }

}
