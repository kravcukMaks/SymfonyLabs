<?php

namespace App\Service;

use App\Entity\Borrow;
use App\Entity\Book;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Validator\BorrowValidator;

class BorrowService
{
    public function __construct(
        private EntityManagerInterface $em,
        private BorrowValidator $validator
    ) {}

    public function create(array $data): Borrow
    {
        $this->validator->validateCreate($data);

        $borrow = new Borrow();

        $user = $this->em->getRepository(User::class)->find($data['user_id']);
        $book = $this->em->getRepository(Book::class)->find($data['book_id']);

        $borrow->setUser($user);
        $borrow->setBook($book);
        $borrow->setBorrowDate(new \DateTimeImmutable());

        $this->em->persist($borrow);
        $this->em->flush();

        return $borrow;
    }

    public function returnBook(Borrow $borrow): Borrow
    {
        $borrow->setReturnDate(new \DateTimeImmutable());
        $this->em->flush();

        return $borrow;
    }

    public function delete(Borrow $borrow): void
    {
        $this->em->remove($borrow);
        $this->em->flush();
    }
}
