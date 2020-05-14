<?php


namespace App\Domain\Search;


use App\Domain\Exceptions\UnknownOrderException;

class PostSearch
{
    const ORDER_BY_CREATED_AT = 'createdAt';
    const ORDER_BY_UPDATED_AT = 'updatedAt';
    const ORDER_BY_USER = 'user';
    const ORDER_BY_TITLE = 'title';

    /** @var array $allowedOrders */
    private static $allowedOrders = [
        self::ORDER_BY_CREATED_AT,
        self::ORDER_BY_UPDATED_AT,
        self::ORDER_BY_USER,
        self::ORDER_BY_TITLE
    ];

    /** @var array $filters */
    private $filters;

    /** @var array|OrderSearch[] $orders */
    private $orders;

    /** @var int $page */
    private $page;

    /** @var int $items */
    private $items;

    /**
     * PostSearch constructor.
     * @param array $filters
     * @param array $orders
     * @param int $page
     * @param int $items
     *
     * @throws UnknownOrderException
     */
    public function __construct(
        array $filters = [],
        array $orders = [],
        int $page = 1,
        int $items = 5
    ) {
        $this->isValidOrders($orders);

        // TODO check allow filters
        $this->filters = $filters;
        $this->orders = $orders;
        $this->page = $page;
        $this->items = $items;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getItems(): int
    {
        return $this->items;
    }

    public function getOrders(): array
    {
        return $this->orders;
    }

    /**
     * @param array $orders
     * @throws UnknownOrderException
     */
    private function isValidOrders(array $orders): void
    {
        /** @var OrderSearch $order */
        foreach ($orders as $order) {
            if (!in_array($order->getOrder(), self::$allowedOrders)) {
                throw new UnknownOrderException(
                    sprintf('Allowed orders are %s', implode(', ',self::$allowedOrders)),
                    400
                );
            }
        }
    }
}