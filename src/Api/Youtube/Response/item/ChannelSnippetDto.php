<?php

namespace Lo\Crawler\Api\Youtube\Response\Item;

use yii\helpers\ArrayHelper;

/**
 * Class ChannelSnippetDto
 *
 * @package Lo\Crawler\Api\Youtube\Response\Item
 */
class ChannelSnippetDto extends BaseSnippetDto
{
    public $path_alias;

    public function populate(): void
    {
        parent::populate();
        $this->path_alias = ArrayHelper::getValue($this->data, 'customUrl');
    }

    public function getPathAlias(): string
    {
        return $this->path_alias ?: $this->date_create;
    }
}
