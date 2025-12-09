<?php

namespace App\Controller;

use App\Service\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository;
use App\Service\PaginationService;

#[Route('/authors')]
class AuthorController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(
        Request $request,
        AuthorRepository $repo,
        PaginationService $pager
    ): Response {
        $search = $request->query->get('search');
        $page   = (int) $request->query->get('page', 1);
        $limit  = (int) $request->query->get('limit', 10);

        $result = $pager->paginate($repo->search($search), $page, $limit);

        return $this->json([
            'items' => array_map(fn(Author $a) => [
                'id'   => $a->getId(),
                'name' => $a->getName(),
            ], $result['items']),
            'page'  => $result['page'],
            'limit' => $result['limit'],
            'count' => $result['count'],
        ]);
    }

}
