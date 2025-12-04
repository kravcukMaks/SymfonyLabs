<?php

namespace App\Service;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Validator\GenreValidator;

class GenreService
{
    public function __construct(
        private EntityManagerInterface $em,
        private GenreValidator $validator
    ) {}

    public function create(array $data): Genre
    {
        $this->validator->validateCreate($data);

        $genre = new Genre();
        $genre->setName($data['name']);

        $this->em->persist($genre);
        $this->em->flush();

        return $genre;
    }

    public function update(Genre $genre, array $data): Genre
    {
        if (isset($data['name'])) {
            $genre->setName($data['name']);
        }

        $this->em->flush();
        return $genre;
    }

    public function delete(Genre $genre): void
    {
        $this->em->remove($genre);
        $this->em->flush();
    }
}
