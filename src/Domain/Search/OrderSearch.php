<?php


namespace App\Domain\Search;


use App\Domain\Exceptions\UnknownDirectionException;

class OrderSearch
{
    /** @var string $order */
    private $order;

    /** @var string $direction */
    private $direction;

    const ALLOW_DIRECTION = [
        'asc',
        'desc'
    ];

    /**
     * SearchOrder constructor.
     * @param string $order
     * @param string $direction
     * @throws UnknownDirectionException
     */
    public function __construct(
        string $order,
        string $direction
    ) {
        if (!in_array($direction, self::ALLOW_DIRECTION)) {
            throw new UnknownDirectionException("Invalid direction: $direction");
        }

        $this->order = $order;
        $this->direction = $direction;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}