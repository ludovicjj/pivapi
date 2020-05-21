<?php


namespace App\Domain\Core\Hateoas\Link;


use App\Domain\Core\Hateoas\AbstractLink;

class LinkGetOne extends AbstractLink
{
    protected $type = 'self';

    protected $method = 'GET';
}