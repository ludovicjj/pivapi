<?php


namespace App\Domain\Core\Hateoas\Link;


use App\Domain\Core\Hateoas\AbstractLink;

class LinkDelete extends AbstractLink
{
    protected $type = 'delete';

    protected $method = 'DELETE';
}