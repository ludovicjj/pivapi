<?php


namespace App\Action\Post;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeletePost
{
    /**
     * @Route("/api/posts/{postId}", name="api_delete_post", methods={"DELETE"})
     * @param Request $request
     */
    public function delete(Request $request)
    {
    }
}