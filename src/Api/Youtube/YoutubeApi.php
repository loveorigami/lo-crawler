<?php

namespace modules\feed\services;

use DomainException;
use Lo\Crawler\CrawlerInterface;
use Exception;
use Throwable;
use yii\helpers\Json;

class YoutubeApi
{
    private const URL_CHANNELS = 'channels';

    protected const ONE_DAY = 86400;
    protected const THREE_DAYS = 259200;

    protected $apiKey;
    protected $client;

    public function __construct($key, CrawlerInterface $client)
    {
        if (\is_string($key) && !empty($key)) {
            $this->apiKey = $key;
        } else {
            throw new \RuntimeException('Google API key is Required, please visit https://console.developers.google.com/');
        }

        $this->client = $client;
    }

    /**
     * @param       $username
     * @param       $optionalParams
     * @param array $part
     * @return ChannelItemDto
     * @throws Exception
     */
    public function getChannelByName($username, $optionalParams = [], $part = ['id', 'snippet']): ChannelItemDto
    {
        $params = \array_merge([
            'key' => $this->apiKey,
            'forUsername' => $username,
            'part' => \implode(', ', $part),
            'type' => 'video',
            'maxResults' => 50,
        ], $optionalParams);

        try {
            $data = $this->client
                ->toCache(self::ONE_DAY)
                ->get(self::URL_CHANNELS, $params)
                ->data();

            return ChannelItemDto::populate($this->decode($data));

        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage());
        }
    }

    /**
     * @param       $id
     * @param array $optionalParams
     * @param array $part
     * @return ChannelItemDto|null
     */
    public function getChannelById($id, $optionalParams = [], $part = ['id', 'snippet']): ChannelItemDto
    {
        $params = \array_merge([
            'key' => $this->apiKey,
            'id' => \is_array($id) ? \implode(',', $id) : $id,
            'part' => \implode(', ', $part),
            'type' => 'video',
            'maxResults' => 50,
        ], $optionalParams);

        try {
            $data = $this->client
                ->toCache(self::ONE_DAY)
                ->get(self::URL_CHANNELS, $params)
                ->data();

            return ChannelItemDto::populate($this->decode($data));

        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage());
        }
    }

    public function saveImagesToStorage(RemoteImageInterface $img): void
    {
        foreach ($img->getPrefixes() as $prefix) {
            $url = $img->getImageUrl($prefix);
            $filename = $img->getImageFileName($prefix);
            $path = $img->getImageDir() . '/' . $filename;

            try {
                $this->client->file($path)->get($url)->save();
                $img->setStorageImage($filename);
            } catch (Throwable $exception) {

            }
        }
    }

    protected function decode(string $data): array
    {
        return Json::decode($data);
    }
}
