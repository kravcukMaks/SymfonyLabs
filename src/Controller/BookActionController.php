<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookActionController extends AbstractController
{
    #[Route('/books/{id}/rating', methods: ['GET'])]
    public function getRating(Book $book): Response
    {
        $reviews = $book->getReviews();
        $count = count($reviews);

        if ($count === 0) {
            return $this->json(['rating' => null]);
        }

        $sum = 0;
        foreach ($reviews as $review) {
            $sum += $review->getRating();
        }

        return $this->json([
            'rating' => round($sum / $count, 2)
        ]);
    }
}
