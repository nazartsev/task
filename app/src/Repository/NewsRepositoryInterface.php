<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\ORM\EntityNotFoundException;

interface NewsRepositoryInterface
{
    /**
     * @param $id
     *
     * @return News
     *
     * @throws EntityNotFoundException
     */
    public function getById($id): News;

    /**
     * @param string $slug
     *
     * @return News
     *
     * @throws EntityNotFoundException
     */
    public function getBySlug(string $slug): News;

    /**
     * @param int | null $limit
     * @param int | null $offset
     *
     * @return array | News[]
     */
    public function findNewsByLimitAndOffset(?int $limit = 20, ?int $offset = 0): array;
}