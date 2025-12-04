<?php

namespace App\Controller;

use App\Entity\Fine;
use App\Form\FineType;
use App\Repository\FineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fine')]
class FineController extends AbstractController
{
    #[Route('/', name: 'app_fine_index', methods: ['GET'])]
    public function index(FineRepository $fineRepository): Response
    {
        return $this->render('fine/index.html.twig', [
            'fines' => $fineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fine = new Fine();
        $form = $this->createForm(FineType::class, $fine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fine);
            $entityManager->flush();

            return $this->redirectToRoute('app_fine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fine/new.html.twig', [
            'fine' => $fine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fine_show', methods: ['GET'])]
    public function show(Fine $fine): Response
    {
        return $this->render('fine/show.html.twig', [
            'fine' => $fine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fine $fine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FineType::class, $fine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fine/edit.html.twig', [
            'fine' => $fine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fine_delete', methods: ['POST'])]
    public function delete(Request $request, Fine $fine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fine->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fine_index', [], Response::HTTP_SEE_OTHER);
    }
}
