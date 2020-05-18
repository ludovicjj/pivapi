<?php


namespace App\Domain\Core\Hateoas\Link;


use App\Domain\Core\Hateoas\AbstractLink;

class LinkGetList extends AbstractLink
{
    protected $type = 'list';

    protected $method = 'GET';
}