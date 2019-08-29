<?php

namespace Lo\Crawler\Api\Youtube\Response\Item;

use lo\core\helpers\DateHelper;
use Lo\Crawler\Api\DbImageTrait;
use Lo\Crawler\Api\RemoteImageInterface;
use yii\helpers\ArrayHelper;

/**
 * Class VideoItemDto
 *
 * --------------
 * [kind] => youtube#searchResult
 * [etag] => "nlUZBA6NbTS7q9G8D1GljyfTIWI/k30VJpJTzqhTCJDj1XhLH8tFErc"
 * [id] => Array
 * (
 *      [kind] => youtube#video
 *      [videoId] => F8JjWnAkp08
 * )
 * [snippet] => Array
 * (
 *      [publishedAt] => 2011-02-26T15:43:20.000Z
 *      [channelId] => UCdJSrOR98XsC_SLqIUg9k_g
 *      [title] => Origami TV Champion-1
 *      [description] => Round 1.
 *      [thumbnails] => Array
 *      (
 *          [default] => Array
 *          (
 *              [url] => https://i.ytimg.com/vi/F8JjWnAkp08/default.jpg
 *              [width] => 120
 *              [height] => 90
 *          )
 *
 *          [medium] => Array
 *          (
 *              [url] => https://i.ytimg.com/vi/F8JjWnAkp08/mqdefault.jpg
 *              [width] => 320
 *              [height] => 180
 *          )
 *
 *          [high] => Array
 *          (
 *              [url] => https://i.ytimg.com/vi/F8JjWnAkp08/hqdefault.jpg
 *              [width] => 480
 *              [height] => 360
 *          )
 *
 *      )
 *
 *      [channelTitle] => Love Origami
 *      [liveBroadcastContent] => none
 * )
 *
 * @package modules\feed\components\youtube\dto
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class VideoItemDto implements RemoteImageInterface
{
    public const PS = '_s';
    public const PH = '_h';

    use DbImageTrait;

    public $item;

    /** @var VideoSnippetDto */
    public $snippet;
    public $id;

    public function __construct(array $data)
    {
        $this->populate($data);
    }

    public function populate(array $data): self
    {
        $this->id = ArrayHelper::getValue($data, 'id.videoId');

        $this->snippet = new VideoSnippetDto($data['snippet']);

        return $this;
    }

    /**
     * @return string
     */
    public function getImageDir(): string
    {
        return 'video/' . DateHelper::pathFull($this->snippet->date_create);
    }

    /**
     * @return array
     */
    public function getPrefixes(): array
    {
        return [self::PH, self::PS];
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

            return $this->snippet->image_h;
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
            return $this->id . $prefix . '.jpg';
        }

        return null;
    }

    public function getChannelId(): string
    {
        return $this->snippet->channelId;
    }

    public function getCreatedAt(): int
    {
        return (int)$this->snippet->date_create;
    }
}
