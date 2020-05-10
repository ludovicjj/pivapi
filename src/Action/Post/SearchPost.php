<?php


namespace App\Action\Post;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchPost
{
    /**
     * @Route("/api/posts", name="api_search_post", methods={"GET"})
     * @param Request $request
     */
    public function search(Request $request)
    {

    }
}