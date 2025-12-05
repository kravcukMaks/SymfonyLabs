<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestCheckerService
{
    public function check(Request $request, array $requiredFields): array
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            throw new BadRequestHttpException(json_encode([
                'body' => ['Invalid or empty JSON body']
            ]));
        }

        $errors = [];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                $errors[$field][] = "Field '$field' is required.";
            }
        }

        if ($errors) {
            throw new BadRequestHttpException(json_encode($errors, JSON_UNESCAPED_UNICODE));
        }

        return $data;
    }
}
