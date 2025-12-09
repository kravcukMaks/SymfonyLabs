<?php

use App\Repository\BookRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/books')]
class BookController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(
        Request $request,
        BookRepository $repo,
        PaginationService $pager
    ): Response {
        $search = $request->query->get('search');
        $page   = (int) $request->query->get('page', 1);
        $limit  = (int) $request->query->get('limit', 10);

        $result = $pager->paginate($repo->search($search), $page, $limit);

        return $this->json([
            'items' => array_map(fn($b) => [
                'id'    => $b->getId(),
                'title' => $b->getTitle(),
            ], $result['items']),
            'page'  => $result['page'],
            'limit' => $result['limit'],
            'count' => $result['count'],
        ]);
    }
}
