<?php

namespace App\Service;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Validator\AuthorValidator;

class AuthorService
{
    public function __construct(
        private EntityManagerInterface $em,
        private AuthorValidator $validator
    ) {}

    public function create(array $data): Author
    {
        $this->validator->validateCreate($data);

        $author = new Author();
        $author->setName($data['name']);
        $author->setBiography($data['biography'] ?? null);
        $author->setBirthDate(
            isset($data['birthDate'])
                ? new \DateTimeImmutable($data['birthDate'])
                : null
        );

        $this->em->persist($author);
        $this->em->flush();

        return $author;
    }
}
