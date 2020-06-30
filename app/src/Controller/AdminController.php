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
        $content = $request->getContent();
        $data = json_decode($content, true);
        $publishedAt = DateTime::createFromFormat('Y-m-d H:i:s', $data['publishedAt']);
        try {
            $newsEntity = new News();
            $newsEntity->setTitle($data['title']);
            $newsEntity->setSlug($data['slug']);
            $newsEntity->setDescription($data['description']);
            $newsEntity->setShortDescription($data['shortDescription']);
            $newsEntity->setCreatedAt($now);
            $newsEntity->setUpdatedAt($now);
            $newsEntity->setPublishedAt($publishedAt);
            $newsEntity->setIsActive($data['isActive']);
            $newsEntity->setIsHidden($data['isHidden']);
            $newsEntity->setHits(0);

            $errors = $validator->validate($newsEntity);
            if (count($errors) > 0) {
                return $this->json(['errors' => (string) $errors], 400);
            }

            $entityManager->persist($newsEntity);
            $entityManager->flush();

            $response = $this->json(['id' => $newsEntity->getId()], 201);
        } catch (Exception $e) {
            $response = $this->json(['errors' => $e->getMessage()], 400);
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
        $content = $request->getContent();
        $data = json_decode($content, true);
        $publishedAt = DateTime::createFromFormat('Y-m-d H:i:s', $data['publishedAt']);
        try {
            $newsEntity = $this->getDoctrine()->getRepository(News::class)->find($id);
            if ($newsEntity) {
                $newsEntity->setTitle($data['title']);
                $newsEntity->setSlug($data['slug']);
                $newsEntity->setDescription($data['description']);
                $newsEntity->setShortDescription($data['shortDescription']);
                $newsEntity->setUpdatedAt($now);
                $newsEntity->setPublishedAt($publishedAt);
                $newsEntity->setIsActive($data['isActive']);
                $newsEntity->setIsHidden($data['isHidden']);

                $errors = $validator->validate($newsEntity);
                if (count($errors) > 0) {
                    return $this->json(['errors' => (string) $errors], 400);
                }

                $entityManager->persist($newsEntity);
                $entityManager->flush();

                $response = $this->json(['id' => $newsEntity->getId()], 200);
            } else {
                $response = $this->json(null, 404);
            }
        } catch (Exception $e) {
            $response = $this->json(['errors' => $e->getMessage()], 400);
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

            $response = $this->json(null, 204);
        } else {
            $response = $this->json(null, 404);
        }

        return $response;
    }
}
