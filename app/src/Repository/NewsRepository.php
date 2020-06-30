<?php

namespace App\Repository;

use App\Entity\News;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method News[]    findFilteredList(?int $limit = 20, int $offset = 0)
 * @method News|null findOneBySlug(string $slug)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @param int|null $limit
     * @param int $offset
     * 
     * @return News[] Returns an array of News objects
     */
    public function findFilteredList(?int $limit = 20, int $offset = 0) : array
    {
        $now = new DateTime();

        return $this->createQueryBuilder('n')
            ->andWhere('n.is_hidden = false')
            ->andWhere('n.is_active = true')
            ->andWhere('n.published_at <= :val')
            ->setParameter('val', $now->format('Y-m-d H:i:s'))
            ->orderBy('n.published_at', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string $slug
     * 
     * @return News|null Returns News object found by slug
     */
    public function findOneBySlug(string $slug): ?News
    {
        $now = new DateTime();

        return $this->createQueryBuilder('n')
            ->andWhere('n.slug = :slug')
            ->andWhere('n.is_hidden = false')
            ->andWhere('n.is_active = true')
            ->andWhere('n.published_at <= :time')
            ->setParameter('time', $now->format('Y-m-d H:i:s'))
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findDirectById(int $id): ?News
    {
        $now = new DateTime();

        return $this->createQueryBuilder('n')
            ->andWhere('n.id = :id')
            ->andWhere('(n.is_hidden = false and n.is_active = true) or (n.is_hidden = true and n.is_active = true)')
            ->andWhere('n.published_at <= :time')
            ->setParameter('time', $now->format('Y-m-d H:i:s'))
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
