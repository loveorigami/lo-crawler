<?php

namespace Lo\Crawler\Api\Youtube\Response\Items;

use Symfony\Component\String\Slugger\AsciiSlugger;
use yii\helpers\ArrayHelper;

class ChannelItem extends BaseItem
{
    /** @var \Lo\Crawler\Api\Youtube\Response\Items\Snippets\ChannelSnippet */
    public $snippet;

    public $uploadsPlaylist;

    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $this->uploadsPlaylist = ArrayHelper::getValue($this->contentDetails, 'relatedPlaylists.uploads');
    }

    /**
     * @return string
     */
    public function getImageDir(): string
    {
        return 'user';
    }

    /**
     * @return array
     */
    public function getPrefixes(): array
    {
        return [self::PM, self::PS];
    }

    public function getPathAlias(): string
    {
        $alias = $this->snippet->customUrl ?: $this->snippet->date_create;
        $slugger = new AsciiSlugger();

        return $slugger->slug($alias)->lower()->toString();
    }

    /**
     * @param $prefix
     * @return null|string
     */
    public function getImageFileName(string $prefix = '_s'): ?string
    {
        if ($this->id) {
            return $this->getPathAlias() . $prefix . '.jpg';
        }

        return null;
    }
}
