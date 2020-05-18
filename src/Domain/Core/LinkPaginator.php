<?php


namespace App\Domain\Core;


class LinkPaginator
{
    /** @var array $links */
    private $links;

    /** @var int $nbItems */
    private $nbItems;

    /** @var int $nbPages */
    private $nbPages;

    public function __construct(
        array $links,
        int $nbItems,
        int $nbPages
    ) {
        $this->links = $links;
        $this->nbItems = $nbItems;
        $this->nbPages = $nbPages;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function getNbItems(): int
    {
        return $this->nbItems;
    }

    public function getNbPages(): int
    {
        return $this->nbPages;
    }
}