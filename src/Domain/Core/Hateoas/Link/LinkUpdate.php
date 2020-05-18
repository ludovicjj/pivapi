<?php


namespace App\Domain\Core\Hateoas\Link;


use App\Domain\Core\Hateoas\AbstractLink;

class LinkUpdate extends AbstractLink
{
    protected $type = 'update';

    protected $method = 'POST';
}