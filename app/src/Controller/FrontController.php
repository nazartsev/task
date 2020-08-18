<?php

namespace App\Controller;

use App\Entity\NewsEntity;
use App\CustomResponse\FrontendResponse;
use App\Repository\NewsEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;


/**
 * Class FrontController
 * @package App\Controller
 * @Route("/front")
 */
class FrontController extends AbstractController
{
    /**
     * @param NewsEntityRepository $newsEntityRepository
     * @param FrontendResponse $response
     * @param int $limit
     * @param int $page
     * @return JsonResponse
     * @throws \Exception
     * @Route("/article/{page}/{limit}", name="news_entity_index", requirements={"page"="\d+","limit"="\d+"}, methods={"GET"})
     */
    public function index(NewsEntityRepository $newsEntityRepository, FrontendResponse $response, $page = 1, $limit = 20): JsonResponse
    {
        return $response->showList($newsEntityRepository->findArticles($page, $limit), $page)->flush();
    }

    /**
     * @param NewsEntity|null $newsEntity
     * @param FrontendResponse $response
     * @return JsonResponse
     * @Route("/article/{slug}", name="news_entity_show", methods={"GET"})
     * @Entity("newsEntity", expr="repository.findBySlug(slug)")
     */
    public function show(?NewsEntity $newsEntity, FrontendResponse $response): JsonResponse
    {
       return $response->showPage($newsEntity)->flush();
    }

}
