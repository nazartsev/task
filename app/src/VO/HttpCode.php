<?php

namespace App\VO;

use InvalidArgumentException;

class HttpCode
{
    /**
     * HTTP_BAD_REQUEST код
     */
    public const BAD_REQUEST = 400;

    /**
     * HTTP_NOT_FOUND код
     */
    public const NOT_FOUND = 404;

    /**
     * HTTP_UNAUTHORIZED код
     */
    public const UNAUTHORIZED = 401;

    /**
     * HTTP_INTERNAL_SERVER_ERROR код
     */
    public const INTERNAL_SERVER_ERROR = 500;

    /**
     * Допустимые значения кода ошибки
     */
    private const VALID_VALUES = [
        self::BAD_REQUEST,
        self::NOT_FOUND,
        self::UNAUTHORIZED,
        self::INTERNAL_SERVER_ERROR,
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_VALUES)) {
            throw new InvalidArgumentException('Недопустимое значение кода ошибки');
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}
