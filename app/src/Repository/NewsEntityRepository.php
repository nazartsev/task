<?php

namespace App\Repository;

use App\Entity\NewsEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @method NewsEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsEntity[]    findAll()
 * @method NewsEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsEntity::class);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function findArticles($page, $limit)
    {
        return $this->getBaseQuery()
            ->andWhere('n.isHide = :isHide')->setParameter('isHide', NewsEntity::IS_DISABLED)
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))->setMaxResults($limit)
            ->execute();
    }

    /**
     * @param string $slug
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findBySlug(string $slug)
    {
        return $this->getBaseQuery()
            ->andWhere('n.slug = :slug')->setParameter('slug', $slug)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function findForSitemap()
    {
        return $this->getBaseQuery()
            ->andWhere('n.isHide = :isHide')->setParameter('isHide', NewsEntity::IS_DISABLED)
            ->getQuery()
            ->execute();
    }

    /**
     * @return QueryBuilder
     * @throws \Exception
     */
    private function getBaseQuery() : QueryBuilder
    {
        return $this->createQueryBuilder('n')
            ->where('n.isActive = :isActive')->setParameter('isActive', NewsEntity::IS_ACTIVE)
            ->andWhere('n.publishedAt <= :now')->setParameter('now',
                (new \DateTime())->setTimezone(new \DateTimeZone('Europe/Moscow'))
                    ->format('Y-m-d H:i:s'))
            ->orderBy('n.publishedAt', 'DESC');
    }

}
