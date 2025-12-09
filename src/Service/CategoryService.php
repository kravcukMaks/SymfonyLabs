<?php

namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function create(array $data): Category
    {
        $category = new Category();
        $category->setName($data['name']);

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    public function update(Category $category, array $data): Category
    {
        if (isset($data['name'])) {
            $category->setName($data['name']);
        }

        $this->em->flush();

        return $category;
    }

    public function delete(Category $category): void
    {
        $this->em->remove($category);
        $this->em->flush();
    }
}
