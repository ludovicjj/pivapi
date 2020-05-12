<?php


namespace App\Domain\Core;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OutputSearchResult
{
    /** @var array $items */
    private $items;

    /** @var int|null $nbItems */
    private $nbItems;

    /** @var int $limit */
    private $limit;

    /** @var int $page */
    private $page;

    /** @var Request $request */
    private $request;

    /** @var array $links*/
    private $links = [];

    /** @var UrlGeneratorInterface $urlGenerator */
    private $urlGenerator;

    /**
     * PaginatorResult constructor.
     * @param array $items
     * @param int $limit
     * @param int $nbItems
     * @param int $page
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        array $items,
        int $limit,
        int $nbItems,
        int $page,
        Request $request,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->items = $items;
        $this->nbItems = $nbItems;
        $this->limit = $limit;
        $this->page = $page;
        $this->request = $request;
        $this->urlGenerator = $urlGenerator;
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
        $nbItems = $this->getNbItems() ?: 1;
        return (int) ceil($nbItems / $this->limit);
    }


    public function getLinks(): array
    {
        if ($this->hasPreviousPage()) {
            $this->createLink('previous', $this->getUrl((string)($this->page - 1)));
        }

        $this->createLink('current', $this->getUrl((string)$this->page));

        if ($this->hasNextPage()) {
            $this->createLink('next', $this->getUrl((string)($this->page + 1)));
        }

        return $this->links;
    }

    private function getUrl(string $updatedQueryPage): string
    {
        return $this->urlGenerator->generate(
            $this->request->attributes->get('_route'),
            $this->getQueryAll($updatedQueryPage),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    private function getQueryAll(string $updatedQueryPage): array
    {
        $this->request->query->set('page', $updatedQueryPage);
        return $this->request->query->all();
    }

    /**
     * @param string $href
     * @param string $url
     */
    private function createLink(string $href, string $url): void
    {
        $this->links[$href] = $url;
    }

    private function hasNextPage(): bool
    {
        return $this->page < $this->getNbItems();
    }

    private function hasPreviousPage(): bool
    {
        return $this->page > 1;
    }
}