<?php

namespace Lo\Crawler\Api;

interface RemoteImageInterface
{
    public function getImageDir(): string;

    public function getPrefixes(): array;

    public function getImageFileName(string $prefix): ?string;

    public function getImageUrl(string $prefix): ?string;

    public function setStorageImage(string $filename): void;

    public function getImageDbName($prefix = null): ?string;
}
