<?php

namespace Lo\Crawler\Api\Youtube\Response\Items;

use lo\core\helpers\DateHelper;

class PlaylistItem extends BaseItem
{
    /** @var \Lo\Crawler\Api\Youtube\Response\Items\Snippets\PlaylistSnippet */
    public $snippet;

    /**
     * @return string
     */
    public function getImageDir(): string
    {
        return 'playlist/' . DateHelper::pathFull($this->snippet->date_create);
    }
}
