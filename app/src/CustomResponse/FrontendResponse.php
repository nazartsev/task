<?php
/**
 * Created by PhpStorm.
 * User: vlad
 * Date: 17.08.20
 * Time: 0:56
 */

namespace App\CustomResponse;


use App\Entity\NewsEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class FrontendResponse
{
    protected $serializer;
    protected $message;
    protected $code;

    protected $page;

    public function __construct(SerializerInterface $serializer) {
        $this->serializer = $serializer;
    }

    /**
     * @param array|null $repositoryResult
     * @param int $page
     * @return $this
     */
    public function showList(?array $repositoryResult, int $page) {
        $this->code = Response::HTTP_NOT_FOUND;
        $this->message = [
            'status' => Response::HTTP_NOT_FOUND,
            'page' => $page,
            'articles' => []
        ];
        if(!empty($repositoryResult)) {
            $this->message['status'] = Response::HTTP_OK;
            $this->message['page'] = $page;
            $this->message['articles'] = $repositoryResult;
        }
        return $this;
    }

    /**
     * @param NewsEntity|null $entity
     * @return $this
     */
    public function showPage(?NewsEntity $entity) {

        $this->code = Response::HTTP_NOT_FOUND;
        $this->message = [
            'status' => Response::HTTP_NOT_FOUND,
            'article' => 'Статья не найдена'
        ];

        if(!empty($entity)) {
            $this->message = [
                'status' => Response::HTTP_OK,
                'article' => $entity
            ];
            $this->code = Response::HTTP_OK;
        }

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function flush() : JsonResponse {
        $json = $this->serializer->serialize($this->message, 'json', array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ]));
        return new JsonResponse($json, $this->code, [], true);
    }

}