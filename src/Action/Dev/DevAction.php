<?php


namespace App\Action\Dev;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DevAction
{
    /**
     * @Route("/api/authenticator", name="api_test_authenticator", methods={"POST"})
     * @return JsonResponse
     */
    public function authenticator(): JsonResponse
    {
        return new JsonResponse("hello world", 200);
    }
}