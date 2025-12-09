<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/publisher')]
class PublisherController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(
        Request $request,
        PublisherRepository $repo,
        \App\Service\PaginationService $pager
    ): Response {
        $search = $request->query->get('search');
        $page   = (int) $request->query->get('page', 1);
        $limit  = (int) $request->query->get('limit', 10);

        $result = $pager->paginate($repo->search($search), $page, $limit);

        return $this->json([
            'items' => array_map(fn(Publisher $p) => [
                'id'      => $p->getId(),
                'name'    => $p->getName(),
                'address' => $p->getAddress(),
            ], $result['items']),
            'page'  => $result['page'],
            'limit' => $result['limit'],
            'count' => $result['count'],
        ]);
    }

    #[Route('/new', name: 'app_publisher_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publisher = new Publisher();
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publisher);
            $entityManager->flush();

            return $this->redirectToRoute('app_publisher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publisher/new.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publisher_show', methods: ['GET'])]
    public function show(Publisher $publisher): Response
    {
        return $this->render('publisher/show.html.twig', [
            'publisher' => $publisher,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_publisher_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publisher $publisher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_publisher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publisher/edit.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publisher_delete', methods: ['POST'])]
    public function delete(Request $request, Publisher $publisher, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publisher->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publisher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publisher_index', [], Response::HTTP_SEE_OTHER);
    }
}
