<?php

namespace Lo\Crawler\Api;

/**
 * Trait DbImageTrait
 */
trait DbImageTrait
{
    protected $images = [];

    /**
     * @param $image
     * @return void
     */
    public function setStorageImage(string $image): void
    {
        $this->images[] = $image;
    }

    /**
     * @param string $prefix
     * @return string|null
     */
    public function getImageDbName($prefix): ?string
    {
        /** @var RemoteImageInterface $this */
        $filename = $this->getImageFileName($prefix);

        return \in_array($filename, (array)$this->images, true) ? $filename : null;
    }
}
