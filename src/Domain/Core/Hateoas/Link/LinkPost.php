<?php


namespace App\Domain\Core\Hateoas\Link;


use App\Domain\Core\Hateoas\AbstractLink;

class LinkPost extends AbstractLink
{
    protected $type = 'new';

    protected $method = 'POST';
}