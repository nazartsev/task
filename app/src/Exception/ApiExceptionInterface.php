<?php

namespace App\Exception;

use App\VO\ApiErrorCode;
use App\VO\HttpCode;

interface ApiExceptionInterface
{
    /**
     * @return array | string[]
     */
    public function getErrors(): array;

    /**
     * @return ApiErrorCode
     */
    public function getApiErrorCode(): ApiErrorCode;

    /**
     * @return HttpCode
     */
    public function getHttpCode(): HttpCode;
}
