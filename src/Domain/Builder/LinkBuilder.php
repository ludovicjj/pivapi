<?php


namespace App\Domain\Builder;


use App\Domain\Core\Hateoas\AbstractLink;
use App\Domain\Core\Hateoas\Link\LinkDelete;
use App\Domain\Core\Hateoas\Link\LinkGetList;
use App\Domain\Core\Hateoas\Link\LinkGetOne;
use App\Domain\Core\Hateoas\Link\LinkPost;
use App\Domain\Core\Hateoas\Link\LinkUpdate;

class LinkBuilder
{
    const SHOW_ONE = 'self';
    const LIST = 'list';
    const DELETE = 'delete';
    const NEW = 'new';
    const UPDATE = 'update';

    /**
     * @param string $type
     * @param string $url
     * @return AbstractLink
     */
    public static function build(string $type, string $url): AbstractLink
    {
        $link = null;

        switch ($type) {
            case self::SHOW_ONE:
                $link = new LinkGetOne($url);
                break;
            case self::LIST:
                $link = new LinkGetList($url);
                break;
            case self::DELETE:
                $link = new LinkDelete($url);
                break;
            case self::NEW:
                $link = new LinkPost($url);
                break;
            case self::UPDATE:
                $link = new LinkUpdate($url);
                break;
        }

        return $link;
    }
}