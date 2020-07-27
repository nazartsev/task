<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraints as Assert;

class CreateNewsDataValidator extends AbstractValidator
{
    /**
     * @return array
     */
    protected function getOptionalFields(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getConstraints(): array
    {
        return [
            'title' => $this->getTitleRules(),
            'slug' => $this->getSlugRules(),
            'description' => $this->getTextRules(),
            'short_description' => $this->getTextRules()
        ];
    }

    /**
     * @return array
     */
    private function getTitleRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Type(
                [
                    'type' => 'string'
                ]
            )
        ];
    }

    /**
     * @return array
     */
    private function getSlugRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Regex(
                [
                    'pattern' => '/^[A-Za-z0-9]+(?:-[A-Za-z0-9]+)*$/',
                    'message' => 'Слаг должен состоять из латинских символов и цифр, разделенных коротким тире'
                ]
            )
        ];
    }

    /**
     * @return array
     */
    private function getTextRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Type(
                [
                    'type' => 'string',
                    'message' => 'Введено неверное описание'
                ]
            )
        ];
    }
}