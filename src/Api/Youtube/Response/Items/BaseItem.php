<?php

namespace Lo\Crawler\Api\Youtube\Response\Items;

use Lo\Crawler\Api\DbImageTrait;
use Lo\Crawler\Api\RemoteImageInterface;
use Spatie\DataTransferObject\DataTransferObject;

abstract class BaseItem extends DataTransferObject implements RemoteImageInterface
{
    public const PS = '_s';
    public const PM = '_s';
    public const PH = '_h';

    use DbImageTrait;

    /** @var string */
    public $kind;

    /** @var string */
    public $etag;

    /** @var string */
    public $id;

    /** @var array */
    public $contentDetails;

    /**
     * @return array
     */
    public function getPrefixes(): array
    {
        return [self::PH, self::PS, self::PM];
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
}
