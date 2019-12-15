<?php

namespace Lo\Crawler\Api\Youtube\Response;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class BaseResponse
 *
 * @package Lo\Crawler\Api\Youtube\Response
 */
abstract class BaseResponse extends DataTransferObject
{
    /** @var string */
    public $kind;

    /** @var string */
    public $etag;

    /** @var \Lo\Crawler\Api\Youtube\Response\Data\PageInfo */
    public $pageInfo;

    /** @var null|string */
    public $nextPageToken;

    /** @var null|string */
    public $prevPageToken;
}
