<?php

namespace App\Service;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Validator\BookValidator;

class BookService
{
    public function __construct(
        private EntityManagerInterface $em,
        private BookValidator $validator
    ) {}

    public function create(array $data): Book
    {
        $this->validator->validateCreate($data);

        $book = new Book();
        $book->setTitle($data['title']);
        $book->setIsbn($data['isbn'] ?? null);
        $book->setPublishedAt(
            isset($data['published_at'])
                ? new \DateTimeImmutable($data['published_at'])
                : null
        );

        $this->em->persist($book);
        $this->em->flush();

        return $book;
    }
}
