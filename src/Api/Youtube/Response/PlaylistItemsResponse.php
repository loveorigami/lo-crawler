<?php

namespace Lo\Crawler\Api\Youtube\Response;

/**
 * Class VideoListDto
 *
 * @package Lo\Crawler\Api\Youtube\Response
 */
class PlaylistItemsResponse extends BaseResponse
{
    /** @var \Lo\Crawler\Api\Youtube\Response\Items\VideoItem[] */
    public $items;
}
