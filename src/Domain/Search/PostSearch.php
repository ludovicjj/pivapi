<?php


namespace App\Domain\Search;


class PostSearch
{
    /** @var array $filters */
    private $filters;

    /** @var int $page */
    private $page;

    /** @var int $items */
    private $items;

    /** @var array $orders */
    private $orders;

    public function __construct(
        array $filters = [],
        int $page = 1,
        int $items = 5,
        array $orders = []
    ) {
        // TODO check filters

        //TODO check orders

        $this->filters = $filters;
        $this->page = $page;
        $this->items = $items;
        $this->orders = $orders;
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
}