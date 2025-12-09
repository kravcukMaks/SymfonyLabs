<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Service\GenreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/genres')]
class GenreController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(
        Request $request,
        GenreRepository $repo,
        \App\Service\PaginationService $pager
    ): Response {
        $search = $request->query->get('search');
        $page   = (int) $request->query->get('page', 1);
        $limit  = (int) $request->query->get('limit', 10);

        $result = $pager->paginate($repo->search($search), $page, $limit);

        return $this->json([
            'items' => array_map(fn(Genre $g) => [
                'id'   => $g->getId(),
                'name' => $g->getName(),
            ], $result['items']),
            'page'  => $result['page'],
            'limit' => $result['limit'],
            'count' => $result['count'],
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, GenreService $service): Response
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $genre = $service->create($data);

        return $this->json([
            'id' => $genre->getId(),
            'name' => $genre->getName()
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Genre $genre): Response
    {
        return $this->json([
            'id' => $genre->getId(),
            'name' => $genre->getName()
        ]);
    }

    #[Route('/{id}', methods: ['PUT','PATCH'])]
    public function update(
        Genre $genre,
        Request $request,
        GenreService $service
    ): Response
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $service->update($genre, $data);

        return $this->json([
            'id' => $genre->getId(),
            'name' => $genre->getName()
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Genre $genre, GenreService $service): Response
    {
        $service->delete($genre);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
