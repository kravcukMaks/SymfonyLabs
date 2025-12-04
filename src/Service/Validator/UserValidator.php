<?php

namespace App\Service\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserValidator
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validateCreate(array $data): void
    {
        $constraint = new Assert\Collection([
            'name' => [
                new Assert\NotBlank(),
                new Assert\Length(max: 255),
            ],
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email(),
                new Assert\Length(max: 255),
            ],
            'phone' => new Assert\Optional([
                new Assert\Length(max: 20),
            ]),
        ]);

        $violations = $this->validator->validate($data, $constraint);

        if (count($violations) > 0) {
            throw new BadRequestHttpException((string)$violations);
        }
    }
}
