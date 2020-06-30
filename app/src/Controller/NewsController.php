<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(NewsRepository $newsRepository, Request $request, PaginatorInterface $paginator)
    {

        $query = $newsRepository->getPublishedNewsQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('news/index.html.twig', [
            'pagination' => json_decode($pagination),
        ]);
    }


    /**
     * @Route("news/{slug}", name="show", methods={"GET"})
     */
    public function show(News $news): Response
    {
        if($news->getIsActive() === false || $news->getPublishedAt() > new \DateTime('now')) {
            new NotFoundHttpException();
        }

        return $this->render('news/show.html.twig', [
            'news' => json_decode($news),
        ]);
    }
}
