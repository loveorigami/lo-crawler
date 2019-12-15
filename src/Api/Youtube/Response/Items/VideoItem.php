<?php

namespace Lo\Crawler\Api\Youtube\Response\Items;

use lo\core\helpers\DateHelper;
use yii\helpers\ArrayHelper;

class VideoItem extends BaseItem
{
    /** @var \Lo\Crawler\Api\Youtube\Response\Items\Snippets\VideoSnippet */
    public $snippet;

    public $videoId;

    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $this->videoId = ArrayHelper::getValue($this->contentDetails, 'videoId');
    }

    /**
     * @return string
     */
    public function getImageDir(): string
    {
        return 'video/' . DateHelper::pathFull($this->snippet->date_create);
    }

    /**
     * @param $prefix
     * @return null|string
     */
    public function getImageFileName(string $prefix = '_s'): ?string
    {
        if ($this->id) {
            return $this->videoId . $prefix . '.jpg';
        }

        return null;
    }
}
