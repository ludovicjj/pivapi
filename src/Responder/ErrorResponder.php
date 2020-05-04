<?php


namespace App\Responder;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponder
{
    /**
     * @param array $data
     * @param int $code
     *
     * @return JsonResponse
     */
    public static function response(array $data, $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return new JsonResponse($data, $code);
    }
}