<?php


namespace App\Domain\Core;


class OutputSearchResult
{
    /** @var array $items */
    private $items;

    /** @var Pagination $links*/
    private $links;

    /**
     * @param array $items
     * @param Pagination $links
     */
    public function __construct(
        array $items,
        Pagination $links
    ) {
        $this->items = $items;
        $this->links = $links;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getNbItems(): int
    {
        return $this->links->getNbItems();
    }

    public function getNbPages(): int
    {
        return $this->links->getNbPages();
    }

    public function getLinks(): array
    {
        return $this->links->getLinks();
    }
}