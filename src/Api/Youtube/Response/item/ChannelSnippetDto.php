<?php

namespace modules\feed\components\youtube\dto\item;

use yii\helpers\ArrayHelper;

/**
 * Class ChannelSnippetDto
 *
 * @package modules\feed\components\youtube\dto\item
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
