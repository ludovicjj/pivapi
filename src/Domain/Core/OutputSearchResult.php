<?php


namespace App\Domain\Core;


class OutputSearchResult
{
    /** @var array $items */
    private $items;

    /** @var int|null $nbItems */
    private $nbItems;

    /**
     * PaginatorResult constructor.
     * @param array $items
     * @param int|null $nbItems
     */
    public function __construct(
        array $items,
        ?int $nbItems = null
    ) {
        $this->items = $items;
        $this->nbItems = $nbItems;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getNbItems(): ?int
    {
        return $this->nbItems;
    }
}