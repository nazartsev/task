<?php

namespace App\Controller;

use App\Builders\NewsBuilder;
use App\Entity\NewsEntity;
use App\CustomResponse\AdminResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 */
class AdminController extends AbstractController
{

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param AdminResponse $response
     * @param NewsBuilder $builder
     * @return JsonResponse
     * @throws \Exception
     * @Route("/article", name="create_article", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator,
                           AdminResponse $response, NewsBuilder $builder): JsonResponse
    {
        $entity = $builder->build($serializer->deserialize($request->getContent(), NewsEntity::class, 'json'));
        if(count($errors = $validator->validate($entity)) > 0) {
            $response->setError($errors, Response::HTTP_BAD_REQUEST);
        } else {
            $eManager = $this->getDoctrine()->getManager();
            $eManager->persist($entity);
            $eManager->flush();
            $response->setMessage("Новость #{$entity->getId()} добавлена", Response::HTTP_OK);
        }
        return $response->flush();
    }

    /**
     * @param $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param AdminResponse $response
     * @param NewsBuilder $builder
     * @return JsonResponse
     * @throws \Exception
     * @Route("/article/{id}/edit", name="update_article", methods={"PUT"})
     */
    public function update($id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator,
                           AdminResponse $response, NewsBuilder $builder): JsonResponse
    {
        $eManager = $this->getDoctrine()->getManager();
        $article = $builder->build($serializer->deserialize($request->getContent(), NewsEntity::class, 'json'), $id);
        if(!empty($article)) {
            if(count($errors = $validator->validate($article)) > 0) {
                $response->setError($errors, Response::HTTP_BAD_REQUEST);
            } else {
                $eManager->flush();
                $response->setMessage("Новость #{$article->getId()} обновлена", Response::HTTP_OK);
            }
        } else {
            $response->setMessage("Новость #{$id} не найдена", Response::HTTP_NOT_FOUND);
        }

        return $response->flush();
    }

    /**
     * @param $id
     * @param AdminResponse $response
     * @param NewsBuilder $builder
     * @return JsonResponse
     * @throws \Exception
     * @Route("/article/{id}", name="delete_article", methods={"DELETE"})
     */
    public function delete($id, AdminResponse $response, NewsBuilder $builder): JsonResponse
    {
        $eManager = $this->getDoctrine()->getManager();
        $article = $builder->build(new NewsEntity(), $id);
        if(!empty($article)) {
           $articleId = $article->getId();
           $eManager->remove($article);
           $eManager->flush();
           $response->setMessage("Новость #{$articleId} удалена", Response::HTTP_OK);
        } else {
            $response->setMessage("Новость не найдена", Response::HTTP_NOT_FOUND);
        }
        return $response->flush();
    }
}
