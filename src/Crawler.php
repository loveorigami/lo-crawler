<?php

namespace Lo\Crawler;

use Carbon\Carbon;
use DomainException;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * Class Crawler
 *
 * @package common\components\parser
 */
class Crawler implements CrawlerInterface
{
    /** @var HttpClient */
    protected $client;

    /** @var CacheItemPoolInterface */
    protected $cache;

    /** @var FilesystemInterface */
    protected $storage;

    /** @var int */
    protected $ttl;

    /** @var bool */
    protected $cached = true;

    /** @var string */
    private $filename;

    /** @var string */
    private $data;

    /**
     * Crawler constructor.
     *
     * @param HttpClient             $client
     * @param CacheItemPoolInterface $cache
     * @param FilesystemInterface    $storage
     */
    public function __construct(
        HttpClient $client,
        CacheItemPoolInterface $cache,
        FilesystemInterface $storage
    ) {
        $this->client = $client;
        $this->cache = $cache;
        $this->storage = $storage;
    }

    /**
     * Время жизни кеша
     *
     * @param int $ttl
     * @return Crawler
     */
    public function toCache(int $ttl = 0): CrawlerInterface
    {
        $this->cached = true;
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * @param string $filename
     * @return Crawler
     */
    public function file(string $filename): CrawlerInterface
    {
        $this->cached = false;
        $this->filename = $filename;

        return $this;
    }

    /**
     * @param $url
     * @return bool
     */
    protected function check($url): bool
    {
        $response = $this->client->request('GET', $url);

        return $response->getStatusCode() === 200;
    }

    /**
     * @param string $url
     * @param array  $params
     * @return Crawler
     * @throws InvalidArgumentException
     */
    public function get(string $url, array $params = []): CrawlerInterface
    {
        if ($this->filename && $this->storage->has($this->filename)) {
            $this->read();
        } else {
            $this->request('GET', $url, $params);
        }

        return $this;
    }

    /**
     * @param string $url
     * @param array  $params
     * @return Crawler
     * @throws InvalidArgumentException
     */
    public function post(string $url, array $params = []): CrawlerInterface
    {
        $this->request('POST', $url, $params);

        return $this;
    }

    /**
     * @param       $method
     * @param       $url
     * @param array $params
     * @return CrawlerInterface
     * @throws InvalidArgumentException
     */
    protected function request(string $method, string $url, array $params = []): CrawlerInterface
    {
        $key = $this->getCacheKey($url, $params);
        $item = $this->cache->getItem($key);
        //echo $key . PHP_EOL;
        if (!$item->isHit()) {
            $response = $this->client->request($method, $url, $params);

            if ($response->getStatusCode() === 200) {
                $data = $response->getBody()->getContents();
                $item->set($data);

                /** Если указано время, иначе по дефолту 0 */
                if ($this->ttl > 0) {
                    $item->expiresAfter($this->ttl);
                }

                /** Если не кешировать */
                if (!$this->cached) {
                    $item->expiresAfter(0);
                }

                $this->cache->save($item);

            } else {
                return $this;
            }
        }

        $this->data = $item->get();

        return $this;
    }

    /**
     * Чтение файла
     *
     * @return CrawlerInterface
     */
    public function read(): CrawlerInterface
    {
        if (!$this->filename) {
            throw new DomainException('File path must be set');
        }

        try {
            $str = $this->storage->read($this->filename);
        } catch (FileNotFoundException $exception) {
            $str = null;
        }

        $this->data = $str;

        return $this;
    }

    /**
     * Ложим файл прямо в хранилище, минуя кеш
     *
     * @param bool $rewrite
     * @return Crawler
     * @throws FileNotFoundException
     */
    public function save($rewrite = false): CrawlerInterface
    {
        if (!$this->filename) {
            throw new DomainException('File path must be set');
        }

        if (!$this->storage->has($this->filename)) {
            $this->storage->put($this->filename, $this->data);
        } elseif ($rewrite) {
            $this->storage->update($this->filename, $this->data);
        }

        $this->filename = null;

        return $this;
    }

    /** @return mixed */
    public function data(): ?string
    {
        return $this->data;
    }

    /**
     * @param string $url
     * @param array  $params
     * @return string
     */
    protected function getCacheKey(string $url, array $params = []): string
    {
        $str = \json_encode($params);

        return \md5($url . $str);
    }

    /**
     * @param string $contentToBeAdded
     * @return bool
     * @throws FileNotFoundException
     */
    protected function toLog($contentToBeAdded = ''): bool
    {
        $file = 'log.txt';

        $content = $this->storage->has($file) ? $this->storage->read($file) . PHP_EOL : '';
        $date = Carbon::now()->toDateTimeString();
        $content .= $date . ' | ' . \trim($contentToBeAdded);

        return $this->storage->put($file, $content, [FILE_APPEND]);
    }
}
