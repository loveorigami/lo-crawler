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
     * 1.
     * Cache time life
     *
     * @param int $ttl
     * @return self
     */
    public function toCache(int $ttl = 0): self;

    /**
     * 1.
     * @param string $filename
     * @return self
     */
    public function file(string $filename): self;

    /**
     * 2.
     * @param string ...$str
     * @return CrawlerInterface
     */
    public function exclude(string ...$str): self;

    /**
     * 3.
     * @param array       $params
     * @param string|null $code
     * @return self
     */
    public function login(array $params, ?string $code = null): self;

    /**
     * 4.
     * @param string $url
     * @param array  $params
     * @return self
     */
    public function get(string $url, array $params = []): self;

    /**
     * 4.
     * @param string $url
     * @param array  $params
     * @return self
     */
    public function post(string $url, array $params = []): self;

    /**
     * File read
     *
     * @return self
     */
    public function read(): self;

    /**
     * @return self
     */
    public function convert(): self;

    /**
     * Put file in storage
     *
     * @param bool $rewrite
     * @return self
     */
    public function save($rewrite = false): self;

    /**
     * @param $url
     * @return bool
     */
    public function check(string $url): bool;

    /** @return string|null */
    public function data(): ?string;
}
