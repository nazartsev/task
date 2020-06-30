<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NewsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FrontController extends AbstractController
{
    /**
     * @Route("/sitemap", name="sitemap", methods={"GET"})
     * @Route("/sitemap.{_format}", name="sitemap_xml", methods={"GET"}, requirements={"_format": "xml"})
     */
    public function sitemap(): BinaryFileResponse
    {
        $filename = __DIR__ . '/../../public/sitemap.xml';
        return new BinaryFileResponse($filename);
    }

    /**
     * @Route("/news", name="front_news_list", methods={"GET"})
     */
    public function list(Request $request) : JsonResponse
    {
        /** @var NewsRepository $newsRepo */
        $newsRepo = $this->getDoctrine()->getRepository(News::class);

        $news = $newsRepo->findFilteredList($request->query->get('limit') ?? 20, $request->query->get('offset') ?? 0);

        return $this->json(['news' => $news], 200);
    }

    /**
     * @Route("/news/{id}", name="front_news_by_id", methods={"GET"})
     */
    public function getById(int $id) : JsonResponse
    {
        /** @var NewsRepository $newsRepo */
        $newsRepo = $this->getDoctrine()->getRepository(News::class);

        $news = $newsRepo->findDirectById($id);

        if ($news) {
            return $this->json(['news' => $news], 200); 
        } else {
            return $this->json(null, 404);
        }
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
            return $this->json(['news' => $news], 200); 
        } else {
            return $this->json(null, 404);
        }
    }
}
