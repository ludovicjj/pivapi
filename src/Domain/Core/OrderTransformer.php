<?php


namespace App\Domain\Core;


use App\Domain\Exceptions\UnknownDirectionException;
use App\Domain\Search\OrderSearch;

class OrderTransformer
{
    /**
     * Transform query param order to array with direction
     *
     * @param string|null $stringOrder
     * @return array|OrderSearch[]
     *@throws UnknownDirectionException
     *
     */
    public static function transformToArray(?string $stringOrder): array
    {
        if (is_null($stringOrder)) {
            return [];
        }

        $orders = explode(',', $stringOrder);
        $arrayOrder = [];

        foreach ($orders as $order) {
            $direction = 'asc';
            if (strpos($order, '-') === 0) {
                $direction = 'desc';
                $order = substr($order, 1);
            }
            $arrayOrder[] = new OrderSearch($order, $direction);
        }
        return $arrayOrder;
    }
}