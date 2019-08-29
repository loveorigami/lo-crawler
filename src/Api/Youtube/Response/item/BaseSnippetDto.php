<?php

namespace Lo\Crawler\Api\Youtube\Response\Item;

use Carbon\Carbon;
use yii\helpers\ArrayHelper;

/**
 * Class ChannelDto
 *
 * --------------
 *  snippet -> array (8) [
 *      'title' => string (32) "Jo Nakashima - Origami Tutorials"
 *      'description' => string (111) "Origami tutorials by Jo Nakashima"
 *      'customUrl' => string (13) "jonakashimabr"
 *      'publishedAt' => string (24) "2006-06-06T23:07:55.000Z"
 *      'thumbnails' => array (3) [
 *          'default' => array (3) [
 *              'url' => string (96) "https://yt3.ggpht.com/a-d-rj-k-no"
 *              'width' => integer 88
 *              'height' => integer 88
 *          ]
 *          'medium' => array (3) [
 *              'url' => string (97) "https://yt3.ggpht.com/a-_qwll4xkwf-rj-k-no"
 *              'width' => integer 240
 *              'height' => integer 240
 *          ]
 *          'high' => array (3) [
 *              'url' => string (97) "https://yt3.ggpht.com/a-/AAuE7mBQ1rRfff-rj-k-no"
 *              'width' => integer 800
 *              'height' => integer 800
 *          ]
 *      ]
 *      'defaultLanguage' => string (2) "en"
 *      'localized' => array (2) [
 *          'title' => string (32) "Jo Nakashima - Origami Tutorials"
 *          'description' => string (111) "Origami tutorials by Jo Nakashima"
 *      ]
 *      'country' => string (2) "BR"
 *  ]
 *
 * @package modules\feed\components\youtube\dto
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
abstract class BaseSnippetDto
{
    protected $data;

    public $title;
    public $description;
    public $date_create;
    public $image_s;
    public $image_m;
    public $image_h;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->populate();
    }

    public function populate(): void
    {
        $this->title = ArrayHelper::getValue($this->data, 'title');
        $this->description = ArrayHelper::getValue($this->data, 'description');

        $publishedAt = ArrayHelper::getValue($this->data, 'publishedAt');
        $this->date_create = Carbon::createFromDate($publishedAt)->timestamp;
        $this->image_s = ArrayHelper::getValue($this->data, 'thumbnails.default.url');
        $this->image_m = ArrayHelper::getValue($this->data, 'thumbnails.medium.url');
        $this->image_h = ArrayHelper::getValue($this->data, 'thumbnails.high.url');
    }
}
