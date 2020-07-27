<?php

namespace App\EventListener;

use App\Exception\ApiExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    /**
     * @param ExceptionEvent $event
     *
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiExceptionInterface) {
            $responseErrors = [];

            foreach ($exception->getErrors() as $field => $errorMessage) {
                $fieldName = is_numeric($field) ? null : $field;
                $errorMessage = is_array($errorMessage) ? json_encode($errorMessage, JSON_UNESCAPED_UNICODE) : $errorMessage;

                $responseErrors['errors'][] = [
                    // В Api в это поле требуется передавать текстовый код ошибки, делаем это через message
                    'code' => $exception->getApiErrorCode()->getValue(),
                    'message' => "$fieldName: $errorMessage",
                    // Если ключ не содержит название поля, определяем field как null
                    'field' => $fieldName,
                ];
            }

            $response = new JsonResponse(
                ['data' => $responseErrors],
                $exception->getHttpCode()->getValue()
            );
        } else {
            $response = new JsonResponse(
                [
                    'data' => [
                        'errors' => [
                            [
                                'code' => 'internal-server-error',
                                'message' => $exception->getMessage(),
                                'line' => $exception->getLine(),
                                'file' => $exception->getFile(),
                            ],
                        ],
                    ],
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        if (!empty($response)) {
            $event->setResponse($response);
        }
    }
}
