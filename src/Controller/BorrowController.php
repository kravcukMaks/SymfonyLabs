<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Repository\BorrowRepository;
use App\Service\BorrowService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/borrows')]
class BorrowController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(
        Request $request,
        BorrowRepository $repo,
        \App\Service\PaginationService $pager
    ): Response {
        $search = $request->query->get('search'); // title/user/status
        $page   = (int) $request->query->get('page', 1);
        $limit  = (int) $request->query->get('limit', 10);

        $result = $pager->paginate($repo->search($search), $page, $limit);

        return $this->json([
            'items' => array_map(fn(Borrow $b) => [
                'id'         => $b->getId(),
                'bookTitle'  => $b->getBook()->getTitle(),
                'userName'   => $b->getUser()->getName(),
                'borrowDate' => $b->getBorrowDate()->format('Y-m-d'),
                'returnDate' => $b->getReturnDate()?->format('Y-m-d'),
                'status'     => $b->getStatus(),
            ], $result['items']),
            'page'  => $result['page'],
            'limit' => $result['limit'],
            'count' => $result['count'],
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, BorrowService $service): Response
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $borrow = $service->create($data);

        return $this->json([
            'id' => $borrow->getId(),
            'user' => $borrow->getUser()->getName(),
            'book' => $borrow->getBook()->getTitle(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}/return', methods: ['PUT'])]
    public function returnBook(Borrow $borrow, BorrowService $service): Response
    {
        $borrow = $service->returnBook($borrow);

        return $this->json([
            'id' => $borrow->getId(),
            'returnDate' => $borrow->getReturnDate()?->format('Y-m-d')
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Borrow $borrow, BorrowService $service): Response
    {
        $service->delete($borrow);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
