<?php

namespace App\Controller;

use App\DTO\DisplayData;
use App\DTO\NewsId;
use App\Exception\ApiBadRequestException;
use App\Exception\ApiNotFoundException;
use App\Service\NewsService;
use App\VO\ApiErrorCode;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @var NewsService
     */
    private $newsService;

    /**
     * @param NewsService $newsService
     */
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * @Route("/sitemap", name="sitemap", methods={"GET"})
     * @Route("/sitemap.{_format}", name="sitemap_xml", methods={"GET"}, requirements={"_format": "xml"})
     *
     * @return BinaryFileResponse
     */
    public function createSitemap(): BinaryFileResponse
    {
        $filename = __DIR__.'/../../public/sitemap.xml';

        return new BinaryFileResponse($filename);
    }

    /**
     * @Route("/news", methods={"GET"})
     *
     * @param DisplayData $displayData
     *
     * @return JsonResponse
     *
     * @throws ApiNotFoundException
     */
    public function displayNewsList(DisplayData $displayData): JsonResponse
    {
        $news = $this->newsService->listNews($displayData);

        if (empty($news)) {
            throw new ApiNotFoundException(
                null,
                new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND)
            );
        }

        return new JsonResponse(
            [
                'news' => $news,
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/news/{id}", methods={"GET"})
     *
     * @param NewsId $id
     *
     * @return JsonResponse
     *
     * @throws ApiBadRequestException
     */
    public function getById(NewsId $id): JsonResponse
    {
        try {
            $news = $this->newsService->getNews($id->getValue());
        } catch (EntityNotFoundException $exception) {
            throw new ApiBadRequestException(
                ['news' => $exception->getMessage()],
                new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND)
            );
        }

        return new JsonResponse(
            [
                'news' => $news,
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/{slug}", name="news_slug", methods={"GET"})
     *
     * @param string $slug
     *
     * @return JsonResponse
     *
     * @throws ApiBadRequestException
     */
    public function getBySlug(string $slug): JsonResponse
    {
        try {
            $news = $this->newsService->getDirectNews($slug);
        } catch (EntityNotFoundException $exception) {
            throw new ApiBadRequestException(
                ['news' => $exception->getMessage()],
                new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND)
            );
        }

        return new JsonResponse(
            [
                'news' => $news,
            ],
            Response::HTTP_OK
        );
    }
}
