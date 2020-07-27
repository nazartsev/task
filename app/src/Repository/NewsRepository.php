<?php

namespace App\Repository;

use App\Entity\News;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News | null    find($id, $lockMode = null, $lockVersion = null)
 * @method News | null    findOneBy(array $criteria, array $orderBy = null)
 */
class NewsRepository extends ServiceEntityRepository implements NewsRepositoryInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @param $id
     *
     * @return News
     *
     * @throws EntityNotFoundException
     */
    public function getById($id): News
    {
        $news = $this->findOneBy(['id' => $id]);

        if (empty($news)) {
            throw new EntityNotFoundException("Новость с id: $id не найдена");
        }

        return $news;
    }

    /**
     * @param string $slug
     *
     * @return News
     *
     * @throws EntityNotFoundException
     */
    public function getBySlug(string $slug): News
    {
        $news = $this->findOneBy(['slug' => $slug]);

        if (empty($news)) {
            throw new EntityNotFoundException("Новость по $slug не найдена");
        }

        return $news;
    }

    /**
     * @param int | null $limit
     * @param int | null $offset
     *
     * @return array | News[]
     */
    public function findNewsByLimitAndOffset(?int $limit = 20, ?int $offset = 0): array
    {
        $now = new DateTime();

        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('n')
            ->from(News::class, 'n')
            ->where('n.isActive = true')
            ->andWhere('n.isHidden = false')
            ->andWhere('n.publishedAt <= :now')
            ->setParameter('now', $now->format('Y-m-d H:i:s'))
            ->orderBy('n.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}
