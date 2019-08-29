<?php

namespace Lo\Crawler\Api\Youtube\Response\Item;

use Lo\Crawler\Api\DbImageTrait;
use Lo\Crawler\Api\RemoteImageInterface;
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
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class ChannelItemDto implements RemoteImageInterface
{
    public const PM = '_m';
    public const PS = '_s';

    use DbImageTrait;

    public $item;
    /**
     * @var ChannelSnippetDto
     */
    public $snippet;
    public $id;

    public static function populate(array $data): self
    {
        $obj = new self;
        $obj->item = ArrayHelper::getValue($data, 'items.0');
        $obj->id = ArrayHelper::getValue($obj->item, 'id');

        if (!$obj->item) {
            $obj->snippet = new ChannelSnippetDto([]);
        } else {
            $obj->snippet = new ChannelSnippetDto($obj->item['snippet']);
        }

        return $obj;
    }

    /**
     * @return string
     */
    public function getImageDir(): string
    {
        return 'yt/user';
    }

    /**
     * @return array
     */
    public function getPrefixes(): array
    {
        return [self::PM, self::PS];
    }

    /**
     * @param string $prefix
     * @return string|null
     */
    public function getImageUrl(string $prefix = '_s'): ?string
    {
        if ($this->id) {
            if ($prefix === self::PS) {
                return $this->snippet->image_s;
            }

            return $this->snippet->image_m;
        }

        return null;
    }

    /**
     * @param $prefix
     * @return null|string
     */
    public function getImageFileName(string $prefix = '_s'): ?string
    {
        if ($this->id) {
            return $this->snippet->getPathAlias() . $prefix . '.jpg';
        }

        return null;
    }
}
