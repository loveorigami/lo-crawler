<?php

namespace Lo\Crawler\Api\Youtube\Response;

/**
 * Class VideoListDto
 *
 * @package Lo\Crawler\Api\Youtube\Response
 */
class PlaylistResponse extends BaseResponse
{
    /** @var \Lo\Crawler\Api\Youtube\Response\Items\PlaylistItem[] */
    public $items;
}
