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
     * @param array       $params
     * @param string|null $code
     * @return self
     */
    public function login(array $params, ?string $code = null): self;

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
     * @param $url
     * @return bool
     */
    public function check(string $url): bool;

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
