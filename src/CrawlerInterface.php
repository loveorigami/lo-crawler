<?php

namespace Lo\Crawler;

/**
 * Interface CrawlerInterface
 *
 * @package Lo\Crawler
 */
interface CrawlerInterface
{
    /**
     * Cache time life
     *
     * @param int $ttl
     * @return self
     */
    public function toCache(int $ttl = 0): self;

    /**
     * @param string $filename
     * @return self
     */
    public function file(string $filename): self;

    /**
     * @param string $url
     * @param array  $params
     * @return self
     */
    public function get(string $url, array $params = []): self;

    /**
     * @param string $url
     * @param array  $params
     * @return self
     */
    public function post(string $url, array $params = []): self;

    /**
     * Чтение файла
     *
     * @return self
     */
    public function read(): self;

    /**
     * Put file in storage
     *
     * @param bool $rewrite
     * @return self
     */
    public function save($rewrite = false): self;


    /** @return string|null */
    public function data(): ?string;
}
