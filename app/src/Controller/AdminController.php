<?php

namespace App\Controller;

use App\Entity\News;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/news", name="admin_news_create", methods={"POST"})
     */
    public function create(Request $request, ValidatorInterface $validator) : JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $now = new DateTime();

        try {
            $newsEntity = new News();
            $newsEntity->setTitle($request->request->get('title'));
            $newsEntity->setSlug($request->request->get('slug'));
            $newsEntity->setDescription($request->request->get('description'));
            $newsEntity->setShortDescription($request->request->get('shortDescription'));
            $newsEntity->setCreatedAt($now);
            $newsEntity->setUpdatedAt($now);
            $newsEntity->setPublishedAt($request->request->get('publishedAt'));
            $newsEntity->setIsActive($request->request->get('isActive'));
            $newsEntity->setIsHidden($request->request->get('isHidden'));

            $errors = $validator->validate($newsEntity);
            if (count($errors) > 0) {
                return new JsonResponse(['errors' => (string) $errors], 400);
            }

            $entityManager->persist($newsEntity);
            $entityManager->flush();

            $response = new JsonResponse(['id' => $newsEntity->getId()], 201);
        } catch (Exception $e) {
            $response = new JsonResponse(['errors' => $e->getMessage()], 400);
        }

        return $response;
    }

    /**
     * @Route("/admin/news/{id}", name="admin_news_update", methods={"PUT"})
     */
    public function update(Request $request, ValidatorInterface $validator, int $id) : JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $now = new DateTime();
        try {
            $newsEntity = $this->getDoctrine()->getRepository(News::class)->find($id);
            if ($newsEntity) {
                $newsEntity->setTitle($request->request->get('title'));
                $newsEntity->setSlug($request->request->get('slug'));
                $newsEntity->setDescription($request->request->get('description'));
                $newsEntity->setShortDescription($request->request->get('shortDescription'));
                $newsEntity->setUpdatedAt($now);
                $newsEntity->setPublishedAt($request->request->get('publishedAt'));
                $newsEntity->setIsActive($request->request->get('isActive'));
                $newsEntity->setIsHidden($request->request->get('isHidden'));

                $errors = $validator->validate($newsEntity);
                if (count($errors) > 0) {
                    return new JsonResponse(['errors' => (string) $errors], 400);
                }

                $entityManager->persist($newsEntity);
                $entityManager->flush();

                $response = new JsonResponse(['id' => $newsEntity->getId()], 200);
            } else {
                $response = new JsonResponse(null, 404);
            }
        } catch (Exception $e) {
            $response = new JsonResponse(['errors' => $e->getMessage()], 400);
        }

        return $response;
    }

    /**
     * @Route("/admin/news/{id}", name="admin_news_delete", methods={"DELETE"})
     */
    public function delete(int $id) : JsonResponse
    {
        $newsEntity = $this->getDoctrine()->getRepository(News::class)->find($id);

        if ($newsEntity) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($newsEntity);
            $entityManager->flush();

            $response = new JsonResponse(null, 204);
        } else {
            $response = new JsonResponse(null, 404);
        }

        return $response;
    }
}
