<?php


namespace App\Domain\Core;


class OutputSearchResult
{
    /** @var array $items */
    private $items;

    /** @var Pagination $pagination */
    private $pagination;

    /**
     * @param array $items
     * @param Pagination $pagination
     */
    public function __construct(
        array $items,
        Pagination $pagination
    ) {
        $this->items = $items;
        $this->pagination = $pagination;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getNbItems(): int
    {
        return $this->pagination->getNbItems();
    }

    public function getNbPages(): int
    {
        return $this->pagination->getNbPages();
    }

    public function getLinks(): array
    {
        return $this->pagination->getLinks();
    }
}