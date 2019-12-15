<?php

namespace Lo\Crawler\Api\Youtube\Response\Items\Snippets;

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
 */
class ChannelSnippet extends BaseSnippet
{
    /** @var null|string */
    public $country;

    /** @var null|string */
    public $customUrl;
}
