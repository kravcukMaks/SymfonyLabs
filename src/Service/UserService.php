<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Validator\UserValidator;

class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserValidator $validator
    ) {}

    public function create(array $data): User
    {
        // Validation
        $this->validator->validateCreate($data);

        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPhone($data['phone'] ?? null);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['name'])) {
            $user->setName($data['name']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['phone'])) {
            $user->setPhone($data['phone']);
        }

        $this->em->flush();
        return $user;
    }

    public function delete(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
