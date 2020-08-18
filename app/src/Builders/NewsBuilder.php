<?php
/**
 * Created by PhpStorm.
 * User: vlad
 * Date: 18.08.20
 * Time: 23:25
 */

namespace App\Builders;


use App\Entity\NewsEntity;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;


class NewsBuilder
{

    private $manager;

    /**
     * NewsBuilder constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ManagerRegistry $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param NewsEntity $load
     * @param int|null $id
     * @return NewsEntity
     * @throws \Exception
     */
    public function build(NewsEntity $load, int $id = null) : ?NewsEntity {

        $entity = (!empty($id)) ? $this->manager->getRepository(NewsEntity::class)->find($id) : new NewsEntity();

        if(!empty($entity)) {
            if (!is_null($load->getTitle())) {
                $entity->setTitle($load->getTitle());
                $entity->setSlug((new Slugify())->slugify($load->getTitle()));
            }

            if (!is_null($load->getShortDescription())) {
                $entity->setShortDescription($load->getShortDescription());
            }

            if (!is_null($load->getDescription())) {
                $entity->setDescription($load->getDescription());
            }

            if (!is_null($load->getPublishedAt())) {
                $publishedAt = $load->getPublishedAt();
                if (!($load->getPublishedAt() instanceof \DateTime)) {
                    $publishedAt = new \DateTime($publishedAt);
                }
                $entity->setPublishedAt($publishedAt);
            }

            if (!is_null($load->getIsActive())) {
                $entity->setIsActive($load->getIsActive());
            }

            if (!is_null($load->getIsHide())) {
                $entity->setIsHide($load->getIsHide());
            }
        }

        return $entity ?? null;
    }
}