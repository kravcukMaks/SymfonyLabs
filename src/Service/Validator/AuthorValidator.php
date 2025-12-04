<?php

namespace App\Service\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthorValidator
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validateCreate(array $data): void
    {
        $constraint = new Assert\Collection([
            'name' => [
                new Assert\NotBlank(),
                new Assert\Length(max: 255),
            ],
            'biography' => new Assert\Optional([
                new Assert\Type('string')
            ]),
            'birthDate' => new Assert\Optional([
                new Assert\Date()
            ]),
        ]);

        $violations = $this->validator->validate($data, $constraint);

        if (count($violations)) {
            throw new BadRequestHttpException((string)$violations);
        }
    }
}
