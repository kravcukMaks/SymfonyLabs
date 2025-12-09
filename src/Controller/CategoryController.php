<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use App\Service\PaginationService;
use App\Service\RequestCheckerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(
        Request $request,
        CategoryRepository $repo,
        \App\Service\PaginationService $pager
    ): Response {
        $search = $request->query->get('search');
        $page   = (int) $request->query->get('page', 1);
        $limit  = (int) $request->query->get('limit', 10);

        $result = $pager->paginate($repo->search($search), $page, $limit);

        return $this->json([
            'items' => array_map(fn(Category $c) => [
                'id'   => $c->getId(),
                'name' => $c->getName(),
            ], $result['items']),
            'page'  => $result['page'],
            'limit' => $result['limit'],
            'count' => $result['count'],
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, CategoryService $service, RequestCheckerService $checker): Response
    {
        $data = $checker->check($request, ['name']);
        $category = $service->create($data);

        return $this->json(['id' => $category->getId(), 'name' => $category->getName()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->json(['id' => $category->getId(), 'name' => $category->getName()]);
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(Category $category, Request $request, CategoryService $service): Response
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $service->update($category, $data);

        return $this->json(['id' => $category->getId(), 'name' => $category->getName()]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Category $category, CategoryService $service): Response
    {
        $service->delete($category);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
