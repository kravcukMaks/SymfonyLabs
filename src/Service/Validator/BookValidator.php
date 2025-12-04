<?php

namespace App\Service\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BookValidator
{
    public function __construct(
        private ValidatorInterface $validator
    ) {}

    public function validateCreate(array $data): void
    {
        $constraint = new Assert\Collection([
            'title' => [
                new Assert\NotBlank(message: 'Назва книги обовʼязкова.'),
                new Assert\Length(max: 255),
            ],
            'isbn' => new Assert\Optional([
                new Assert\Length(max: 20),
            ]),
            'pages' => new Assert\Optional([
                new Assert\Type('integer'),
            ]),
            'published_at' => new Assert\Optional([
                new Assert\Date(),
            ]),
        ]);

        $this->throwIfInvalid($data, $constraint);
    }

    private function throwIfInvalid(array $data, Assert\Collection $constraint): void
    {
        $violations = $this->validator->validate($data, $constraint);

        if (count($violations) > 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $field = $violation->getPropertyPath();
                $errors[$field][] = $violation->getMessage();
            }

            throw new BadRequestHttpException(json_encode($errors, JSON_UNESCAPED_UNICODE));
        }
    }
}
