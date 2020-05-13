<?php


namespace App\Domain\Core;


class OutputSearchResult
{
    /** @var array $items */
    private $items;

    /** @var int $nbItems */
    private $nbItems;

    /** @var int $nbPages */
    private $nbPages;

    /** @var array $links*/
    private $links = [];

    /**
     * PaginatorResult constructor.
     * @param array $items
     * @param int $nbItems
     * @param int $nbPages
     * @param array $links
     */
    public function __construct(
        array $items,
        int $nbItems,
        int $nbPages,
        array $links
    ) {
        $this->items = $items;
        $this->nbItems = $nbItems;
        $this->nbPages = $nbPages;
        $this->links = $links;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getNbItems(): ?int
    {
        return $this->nbItems;
    }

    public function getNbPages(): int
    {
        return $this->nbPages;
    }

    public function getLinks(): array
    {
        return $this->links;
    }
}