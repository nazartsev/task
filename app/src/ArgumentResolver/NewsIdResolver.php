<?php

namespace App\ArgumentResolver;

use App\DTO\NewsId;
use App\Exception\ApiNotFoundException;
use App\Validation\NewsIdValidator;
use App\VO\ApiErrorCode;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class NewsIdResolver implements ArgumentValueResolverInterface
{
    /**
     * @var NewsIdValidator
     */
    private $validator;

    /**
     * @param NewsIdValidator $validator
     */
    public function __construct(NewsIdValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return NewsId::class === $argument->getType();
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $id = $request->get('id');

        $errors = $this->validator->validate(['newsId' => $id]);

        if (!empty($errors)) {
            throw new ApiNotFoundException($errors, new ApiErrorCode(ApiErrorCode::NOT_FOUND));
        }

        yield new NewsId($id);
    }
}