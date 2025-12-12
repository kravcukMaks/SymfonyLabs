<?php

namespace App\Controller;

use App\Entity\Borrow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BorrowActionController extends AbstractController
{
    #[Route('/borrows/{id}/return', methods: ['POST'])]
    public function returnBook(Borrow $borrow, EntityManagerInterface $em): Response
    {
        $borrow->setStatus('returned');
        $borrow->setReturnDate(new \DateTime());

        $em->flush();

        return $this->json([
            'message' => 'Book returned successfully',
            'borrowId' => $borrow->getId()
        ]);
    }
}
