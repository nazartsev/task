<?php

namespace App\Service;

use App\DTO\CreateNewsData;
use App\DTO\DisplayData;
use App\DTO\UpdateNewsData;
use App\Entity\News;
use App\Repository\NewsRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;

class NewsService
{
    /**
     * @var NewsRepositoryInterface
     */
    private $newsRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param NewsRepositoryInterface $newsRepository
     * @param EntityManagerInterface  $em
     */
    public function __construct(NewsRepositoryInterface $newsRepository, EntityManagerInterface $em)
    {
        $this->newsRepository = $newsRepository;
        $this->em = $em;
    }

    /**
     * @param CreateNewsData $newsData
     *
     * @return News
     *
     * @throws Exception
     */
    public function create(CreateNewsData $newsData): News
    {
        try {
            $this->em->beginTransaction();

            $news = new News(
                $newsData->getTitle(),
                $newsData->getDescription(),
                $newsData->getShortDescription(),
                $newsData->getSlug(),
                null,
                $newsData->getPublishedAt(),
                true,
                $newsData->isHidden(),
                0
            );

            $this->em->persist($news);
            $this->em->flush();
            $this->em->commit();
        } catch (Exception $exception) {
            $this->em->rollback();

            throw $exception;
        }

        return $news;
    }

    /**
     * @param int            $id
     * @param UpdateNewsData $newsData
     *
     * @return News
     *
     * @throws EntityNotFoundException
     */
    public function update(int $id, UpdateNewsData $newsData): News
    {
        try {
            $news = $this->newsRepository->getById($id);
            $news->update(
                $newsData->getTitle(),
                $newsData->getDescription(),
                $newsData->getShortDescription(),
                $newsData->getSlug(),
                new DateTime(),
                $newsData->getPublishedAt(),
                $newsData->isActive(),
                $newsData->isHidden()
            );
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param int $id
     *
     * @throws EntityNotFoundException
     */
    public function remove(int $id): void
    {
        try {
            $this->em->beginTransaction();
            $news = $this->newsRepository->getById($id);
            $this->em->remove($news);
            $this->em->flush();
            $this->em->commit();
        } catch (Exception $exception) {
            $this->em->rollback();

            throw $exception;
        }
    }

    /**
     * @param DisplayData $displayData
     *
     * @return array
     */
    public function listNews(DisplayData $displayData): array
    {
        return $this->newsRepository->findNewsByLimitAndOffset($displayData->getLimit(), $displayData->getOffset());
    }

    /**
     * @param int $id
     *
     * @return News
     *
     * @throws EntityNotFoundException
     */
    public function getNews(int $id): News
    {
        return $this->newsRepository->getById($id);
    }

    /**
     * @param string $slug
     *
     * @return News
     *
     * @throws EntityNotFoundException
     */
    public function getDirectNews(string $slug): News
    {
        return $this->newsRepository->getBySlug($slug);
    }
}
