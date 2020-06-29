<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NewsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class FrontController extends AbstractController
{
    /**
     * @Route("/news", name="front_news_list", methods={"GET"})
     */
    public function list(Request $request) : JsonResponse
    {
        /** @var NewsRepository $newsRepo */
        $newsRepo = $this->getDoctrine()->getRepository(News::class);

        $news = $newsRepo->findFilteredList($request->query->get('limit') ?? 20, $request->query->get('offset') ?? 0);

        return new JsonResponse(['news' => $news], 200);
    }

    /**
     * @Route("/{slug}", name="front_news_by_slug", methods={"GET"})
     */
    public function getBySlug(string $slug) : JsonResponse
    {
        /** @var NewsRepository $newsRepo */
        $newsRepo = $this->getDoctrine()->getRepository(News::class);

        $news = $newsRepo->findOneBySlug($slug);

        if ($news) {
            return new JsonResponse(['news' => $news], 200); 
        } else {
            return new JsonResponse(null, 404);
        }
    }
}
