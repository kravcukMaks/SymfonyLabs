<?php

namespace App\Service\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BorrowValidator
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validateCreate(array $data): void
    {
        $constraint = new Assert\Collection([
            'user_id' => [
                new Assert\NotBlank(),
                new Assert\Type('integer')
            ],
            'book_id' => [
                new Assert\NotBlank(),
                new Assert\Type('integer')
            ],
        ]);

        $violations = $this->validator->validate($data, $constraint);

        if (count($violations) > 0) {
            throw new BadRequestHttpException((string)$violations);
        }
    }
}
