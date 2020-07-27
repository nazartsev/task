<?php

namespace App\VO;

use InvalidArgumentException;

class ApiErrorCode
{
    /**
     * Неверно указано/не указано поле/несколько полей
     */
    public const VALIDATION_ERROR = 'validation-error';

    /**
     * Ошибка доступа
     */
    public const ACCESS_DENIED = 'access-denied';

    /**
     * Не найдено
     */
    public const NOT_FOUND = 'not-found';

    /**
     * Не доступно
     */
    public const NOT_AVAILABLE = 'not-available';

    /**
     * Сущность уже существует
     */
    public const ENTITY_EXISTS = 'entity-exists';

    /**
     * Сущность не найдена
     */
    public const ENTITY_NOT_FOUND = 'entity-not-found';

    /**
     * Сущность не удалось создать
     */
    public const ENTITY_NOT_CREATED = 'entity-not-created';

    /**
     * Допустимые значения кода ошибки
     */
    private const VALID_VALUES = [
        self::VALIDATION_ERROR,
        self::NOT_FOUND,
        self::ACCESS_DENIED,
        self::ENTITY_NOT_FOUND,
        self::ENTITY_EXISTS,
        self::ENTITY_NOT_CREATED,
        self::NOT_AVAILABLE,
    ];

    /**
     * @var string
     */
    private$value;

    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_VALUES)) {
            throw new InvalidArgumentException("Недопустимое значение кода ошибки $value");
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}