<?php


namespace App\Domain\Search;


class PostSearch
{
    /** @var array $filters */
    private $filters;

    /** @var array $orders */
    private $orders;

    /** @var int $page */
    private $page;

    /** @var int $items */
    private $items;

    public function __construct(
        array $filters = [],
        array $orders = [],
        int $page = 1,
        int $items = 5
    ) {
        // TODO check allow filters
        $this->filters = $filters;
        //TODO check allow orders
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
}