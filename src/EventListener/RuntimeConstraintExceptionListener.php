<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

class RuntimeConstraintExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof BadRequestHttpException) {
            $message = $exception->getMessage();

            $event->setResponse(new JsonResponse([
                'status' => 'error',
                'errors' => json_decode($message, true)
            ], 400));
        }
    }
}
