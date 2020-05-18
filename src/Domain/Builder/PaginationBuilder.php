<?php


namespace App\Domain\Builder;


use App\Domain\Core\Pagination;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaginationBuilder
{
    /** @var UrlGeneratorInterface $urlGenerator */
    private $urlGenerator;

    /** @var RequestStack $requestStack */
    private $requestStack;

    /** @var int|null $perPage */
    private $perPage;

    /** @var int|null $nbItems */
    private $nbItems;

    /** @var int|null $page */
    private $page;

    /** @var array $links */
    private $links = [];

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        RequestStack $requestStack
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
    }

    /**
     * @param int $nbItems
     * @return PaginationBuilder
     * @throws NotFoundHttpException
     */
    public function build(int $nbItems): PaginationBuilder
    {
        $this->perPage = $this->requestStack->getCurrentRequest()->query->get('items');
        $this->nbItems = $nbItems;
        $this->page = $this->requestStack->getCurrentRequest()->query->get('page');

        if ($this->page > $this->getNbPages()) {
            throw new NotFoundHttpException(
                sprintf('Not found page %s', $this->page)
            );
        }

        return $this;
    }

    /**
     * @return Pagination
     */
    public function getPagination(): Pagination
    {
        return new Pagination(
            $this->getLinks(),
            $this->getNbItems(),
            $this->getNbPages()
        );
    }

    private function getLinks(): array
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

    private function getNbPages(): int
    {
        $nbItems = $this->nbItems ?: 1;
        return (int) ceil($nbItems / $this->perPage);
    }

    private function getNbItems(): int
    {
        return $this->nbItems;
    }

    /**
     * @param string $href
     * @param string $url
     */
    private function createLink(string $href, string $url): void
    {
        $this->links[$href] = $url;
    }

    private function getUrl(string $updatedQueryPage): string
    {
        return $this->urlGenerator->generate(
            $this->requestStack->getCurrentRequest()->attributes->get('_route'),
            $this->getQueryAll($updatedQueryPage),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    private function getQueryAll(string $updatedQueryPage): array
    {
        $this->requestStack->getCurrentRequest()->query->set('page', $updatedQueryPage);
        return $this->requestStack->getCurrentRequest()->query->all();
    }

    private function hasNextPage(): bool
    {
        return $this->page < $this->getNbPages();
    }

    private function hasPreviousPage(): bool
    {
        return $this->page > 1;
    }
}