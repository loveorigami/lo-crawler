<?php

namespace Lo\Crawler\Api\Youtube\Response\Items\Snippets;

class VideoSnippet extends BaseSnippet
{
    /** @var string */
    public $channelId;

    /** @var string */
    public $channelTitle;

    /** @var string */
    public $playlistId;

    /** @var int */
    public $position;

    /** @var array */
    public $resourceId;

    /** @var string */
    public $videoOwnerChannelTitle;

    /** @var string */
    public $videoOwnerChannelId;
}
