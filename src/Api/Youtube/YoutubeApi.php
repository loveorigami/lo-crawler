<?php

namespace Lo\Crawler\Api\Youtube;

use DomainException;
use Exception;
use InvalidArgumentException;
use Lo\Crawler\Api\RemoteImageInterface;
use Lo\Crawler\Api\Youtube\Response\ChannelResponse;
use Lo\Crawler\Api\Youtube\Response\PlaylistItemsResponse;
use Lo\Crawler\Api\Youtube\Response\PlaylistResponse;
use Lo\Crawler\CrawlerInterface;
use Throwable;
use yii\helpers\Json;

class YoutubeApi
{
    private const URL_PLAYLIST_ITEMS = 'playlistItems';
    private const URL_PLAYLISTS = 'playlists';
    private const URL_CHANNELS = 'channels';
    private const URL_SEARCH = 'search';

    protected const ONE_DAY = 86400;
    protected const THREE_DAYS = 259200;
    protected const FIVE_DAYS = 432000;

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
     * @return ChannelResponse
     */
    public function getChannelByName($username, $optionalParams = []): ChannelResponse
    {
        $params = \array_merge([
            'key' => $this->apiKey,
            'forUsername' => $username,
            'part' => 'id, snippet, contentDetails',
            'type' => 'video',
            'maxResults' => 50,
        ], $optionalParams);

        try {
            $data = $this->client
                ->asCache(self::ONE_DAY)
                ->get(self::URL_CHANNELS, $params)
                ->data();

            return new ChannelResponse($this->decode($data));

        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage());
        }
    }

    /**
     * @param       $id
     * @param array $optionalParams
     * @return ChannelResponse|null
     */
    public function getChannelById($id, $optionalParams = []): ChannelResponse
    {
        $params = \array_merge([
            'key' => $this->apiKey,
            'id' => $id,
            'part' => 'id, snippet, contentDetails',
            'type' => 'video',
            'maxResults' => 50,
        ], $optionalParams);

        try {
            $data = $this->client
                ->asCache(self::ONE_DAY)
                ->get(self::URL_CHANNELS, $params)
                ->data();

            return new ChannelResponse($this->decode($data));

        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage());
        }
    }

    public function getVideosByPlaylistId($id, $optionalParams = []): PlaylistItemsResponse
    {
        $params = \array_merge([
            'key' => $this->apiKey,
            'playlistId' => $id,
            'part' => 'id, snippet, contentDetails',
            'maxResults' => 50,
        ], $optionalParams);

        try {
            $data = $this->client
                ->asCache(self::FIVE_DAYS)
                ->get(self::URL_PLAYLIST_ITEMS, $params)
                ->data();

            return new PlaylistItemsResponse($this->decode($data));

        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage());
        }
    }

    public function getPlaylistsByChannelId($id, $optionalParams = [])
    {
        $params = \array_merge([
            'key' => $this->apiKey,
            'part' => 'id, snippet',
            'channelId' => $id,
            'maxResults' => 50,
        ], $optionalParams);

        try {
            $data = $this->client
                ->asCache(self::ONE_DAY)
                ->get(self::URL_PLAYLISTS, $params)
                ->data();

            return new PlaylistResponse($this->decode($data));

        } catch (Throwable $exception) {
            throw new DomainException($exception->getMessage());
        }
    }

    /**
     * Generic Search Paginator, use any parameters specified in
     * the API reference and pass through nextPageToken as $token if set.
     *
     * @param $params
     * @param $token
     * @return array
     * @throws Exception
     */
    protected function paginateResults(array $params, $token = null): ?array
    {
        if ($token !== null) {
            $params['pageToken'] = $token;
        }

        return $this->searchAdvanced($params);
    }

    /**
     * Generic Search interface, use any parameters specified in
     * the API reference
     *
     * @param $params
     * @return array
     * @throws Exception
     */
    protected function searchAdvanced(array $params): array
    {
        if (
            !isset($params['q']) &&
            !isset($params['channelId']) &&
            !isset($params['videoCategoryId'])
        ) {
            throw new InvalidArgumentException('at least the Search query or Channel ID or videoCategoryId must be supplied');
        }

        $params['key'] = $this->apiKey;
        $params['order'] = 'date';

        $data = $this->client
            ->asCache(self::FIVE_DAYS)
            ->get(self::URL_SEARCH, $params)
            ->data();

        return $this->decode($data);
    }

    /**
     * @param RemoteImageInterface $img
     */
    public function saveImagesToStorage(RemoteImageInterface $img): void
    {
        foreach ($img->getPrefixes() as $prefix) {
            $url = $img->getImageUrl($prefix);
            $filename = $img->getImageFileName($prefix);
            $path = $img->getImageDir() . '/' . $filename;

            try {
                $this->client->asFile($path)->get($url)->save();
                $img->setStorageImage($filename);
            } catch (Throwable $exception) {
                //exit($exception->getMessage());
            }
        }
    }

    /**
     * @param string $data
     * @return array
     */
    protected function decode(string $data): array
    {
        return Json::decode($data);
    }
}
