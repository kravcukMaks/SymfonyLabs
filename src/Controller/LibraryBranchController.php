<?php

namespace App\Controller;

use App\Entity\LibraryBranch;
use App\Form\LibraryBranchType;
use App\Repository\LibraryBranchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/library/branch')]
class LibraryBranchController extends AbstractController
{
    #[Route('/', name: 'app_library_branch_index', methods: ['GET'])]
    public function index(LibraryBranchRepository $libraryBranchRepository): Response
    {
        return $this->render('library_branch/index.html.twig', [
            'library_branches' => $libraryBranchRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_library_branch_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $libraryBranch = new LibraryBranch();
        $form = $this->createForm(LibraryBranchType::class, $libraryBranch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($libraryBranch);
            $entityManager->flush();

            return $this->redirectToRoute('app_library_branch_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('library_branch/new.html.twig', [
            'library_branch' => $libraryBranch,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_library_branch_show', methods: ['GET'])]
    public function show(LibraryBranch $libraryBranch): Response
    {
        return $this->render('library_branch/show.html.twig', [
            'library_branch' => $libraryBranch,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_library_branch_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LibraryBranch $libraryBranch, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LibraryBranchType::class, $libraryBranch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_library_branch_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('library_branch/edit.html.twig', [
            'library_branch' => $libraryBranch,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_library_branch_delete', methods: ['POST'])]
    public function delete(Request $request, LibraryBranch $libraryBranch, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$libraryBranch->getId(), $request->request->get('_token'))) {
            $entityManager->remove($libraryBranch);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_library_branch_index', [], Response::HTTP_SEE_OTHER);
    }
}
