<?php

namespace App\Exception;

use App\VO\ApiErrorCode;
use App\VO\HttpCode;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiUnauthorizedException extends UnauthorizedHttpException implements ApiExceptionInterface
{
    private const CHALLENGE = 'Basic realm="Access to the api", charset="UTF-8"';

    /**
     * @var array | string[]
     */
    private $errors;

    /**
     * @var ApiErrorCode
     */
    private $apiErrorCode;

    /**
     * @var HttpCode
     */
    private $httpCode;

    /**
     * @param array            $errors
     * @param ApiErrorCode     $apiErrorCode
     * @param string           $message
     * @param int              $code
     * @param Exception | null $previous
     */
    public function __construct(
        array $errors,
        ApiErrorCode $apiErrorCode,
        string $message = '',
        int $code = 0,
        Exception $previous = null
    ) {
        $message = empty($message) ? json_encode($errors) : $message;

        parent::__construct(
            self::CHALLENGE,
            $message,
            $previous,
            $code
        );

        $this->errors = $errors;
        $this->apiErrorCode = $apiErrorCode;
        $this->httpCode = new HttpCode(HttpCode::UNAUTHORIZED);
    }

    /**
     * @return array | string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return ApiErrorCode
     */
    public function getApiErrorCode(): ApiErrorCode
    {
        return $this->apiErrorCode;
    }

    /**
     * @return HttpCode
     */
    public function getHttpCode(): HttpCode
    {
        return $this->httpCode;
    }
}
