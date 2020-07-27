<?php

namespace App\Controller;

use App\DTO\CreateNewsData;
use App\DTO\NewsId;
use App\DTO\UpdateNewsData;
use App\Exception\ApiBadRequestException;
use App\Service\NewsService;
use App\Service\Rabbit\SitemapProducer;
use App\VO\ApiErrorCode;
use DateTime;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @var NewsService
     */
    private $newsService;

    /**
     * @var SitemapProducer
     */
    private $producer;

    /**
     * @param NewsService     $newsService
     * @param SitemapProducer $producer
     */
    public function __construct(NewsService $newsService, SitemapProducer $producer)
    {
        $this->newsService = $newsService;
        $this->producer = $producer;
    }

    /**
     * @Route("/admin/news", methods={"POST"})
     *
     * @param CreateNewsData $newsData
     *
     * @return JsonResponse
     *
     * @throws ApiBadRequestException
     */
    public function createNews(CreateNewsData $newsData): JsonResponse
    {
        if (!$newsData->isActive() || (new DateTime() < $newsData->getPublishedAt())) {
            return new JsonResponse(
                null, Response::HTTP_NOT_FOUND
            );
        }

        try {
            $news = $this->newsService->create($newsData);
        } catch (Exception $exception) {
            throw new ApiBadRequestException(
                ['news' => $exception->getMessage()],
                new ApiErrorCode(ApiErrorCode::ENTITY_NOT_CREATED)
            );
        }

        $this->producer->publish();

        return new JsonResponse(
            [
                'id' => $news->getId(),
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/admin/news/{id}", methods={"PUT"})
     *
     * @param NewsId         $id
     * @param UpdateNewsData $newsData
     *
     * @return JsonResponse
     *
     * @throws ApiBadRequestException
     */
    public function updateNews(NewsId $id, UpdateNewsData $newsData): JsonResponse
    {
        try {
            $news = $this->newsService->update($id->getValue(), $newsData);
        } catch (EntityNotFoundException $exception) {
            throw new ApiBadRequestException(
                ['news' => $exception->getMessage()],
                new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND)
            );
        }

        $this->producer->publish();

        return new JsonResponse(
            [
                'id' => $news->getId(),
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @param NewsId $id
     *
     * @return JsonResponse
     *
     * @throws ApiBadRequestException
     */
    public function deleteNews(NewsId $id): JsonResponse
    {
        try {
            $this->newsService->remove($id->getValue());
        } catch (EntityNotFoundException $exception) {
            throw new ApiBadRequestException(
                ['news' => $exception->getMessage()],
                new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND)
            );
        }

        $this->producer->publish();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
