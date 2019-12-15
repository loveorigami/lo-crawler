<?php

namespace Lo\Crawler\Api\Youtube\Response\Data;

use Spatie\DataTransferObject\DataTransferObject;

class PageInfo extends DataTransferObject
{
    /** @var null|int */
    public $totalResults;

    /** @var null|int */
    public $resultsPerPage;
}
