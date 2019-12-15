<?php

namespace Lo\Crawler\Api\Youtube\Response\Items\Snippets;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;
use yii\helpers\ArrayHelper;

abstract class BaseSnippet extends DataTransferObject
{
    /** @var null|string */
    public $publishedAt;

    /** @var null|string */
    public $title;

    /** @var null|string */
    public $description;

    /** @var null|array */
    public $tags;

    /** @var null|array */
    public $thumbnails;

    /** @var null|string */
    public $defaultLanguage;

    /** @var null|\Lo\Crawler\Api\Youtube\Response\Items\Snippets\Localized */
    public $localized;

    /**-----------------*/

    public $date_create;
    public $image_m;
    public $image_s;
    public $image_h;

    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
        $this->date_create = Carbon::createFromDate($this->publishedAt)->timestamp;
        $this->image_s = ArrayHelper::getValue($this->thumbnails, 'default.url');
        $this->image_m = ArrayHelper::getValue($this->thumbnails, 'medium.url');
        $this->image_h = ArrayHelper::getValue($this->thumbnails, 'high.url');
    }
}
