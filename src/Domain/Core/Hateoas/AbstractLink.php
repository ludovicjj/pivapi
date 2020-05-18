<?php


namespace App\Domain\Core\Hateoas;


abstract class AbstractLink
{
    /** @var string */
    protected $type;

    /** @var string */
    protected $method;

    /** @var string */
    protected $href;

    /**
     * AbstractLink constructor.
     *
     * @param string $href
     */
    public function __construct(
        string $href
    )
    {
        $this->href = $href;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}