<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UserController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(
    Request $request,
    UserRepository $repo,
    \App\Service\PaginationService $pager
 ): Response {
    $search = $request->query->get('search');
    $page   = (int) $request->query->get('page', 1);
    $limit  = (int) $request->query->get('limit', 10);

    $result = $pager->paginate($repo->search($search), $page, $limit);

    return $this->json([
        'items' => array_map(fn(User $u) => [
            'id'   => $u->getId(),
            'name' => $u->getName(),
        ], $result['items']),
        'page'  => $result['page'],
        'limit' => $result['limit'],
        'count' => $result['count'],
    ]);
 }


    #[Route('', methods: ['POST'])]
    public function create(
    Request $request,
    UserService $service,
    \App\Service\RequestCheckerService $checker
    ): Response {
    
    $data = $checker->check($request, ['name', 'email']);

    $user = $service->create($data);

    return $this->json([
        'id' => $user->getId(),
        'name' => $user->getName()
    ], Response::HTTP_CREATED);
 }


    #[Route('/{id}', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName()
        ]);
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(User $user, Request $request, UserService $service): Response
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $service->update($user, $data);

        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName()
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(User $user, UserService $service): Response
    {
        $service->delete($user);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
