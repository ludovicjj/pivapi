<?php


namespace App\Domain\Request;


use App\Domain\Exceptions\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SearchPostRequestHandler
{
    /**
     * @param Request $request
     * @throws InvalidArgumentException
     */
    public function handle(Request $request): void
    {
        $page = $request->query->get('page', 1);
        $items = $request->query->get('items', 5);

        if ((int)$page < 1) {
            throw new NotFoundHttpException(
                sprintf('Not found page %s', $page)
            );
        }

        if ((int)$items < 1) {
            throw new InvalidArgumentException(
                sprintf('Query parameter items must be int and greater than 0, given %s', $items),
                400
            );
        }
    }
}