<?php

namespace Lo\Crawler\Api\Youtube\Response\Item;

use yii\helpers\ArrayHelper;

/**
 * Class VideoSnippetDto
 *
 * @package modules\feed\components\youtube\dto\item
 */
class VideoSnippetDto extends BaseSnippetDto
{
    public $channelId;

    public function populate(): void
    {
        parent::populate();
        $this->channelId = ArrayHelper::getValue($this->data, 'channelId');
    }

    public function getChannelId(): string
    {
        return $this->channelId;
    }
}
