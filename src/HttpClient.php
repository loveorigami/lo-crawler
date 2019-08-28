<?php

namespace Lo\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;
use yii\helpers\ArrayHelper;

/**
 * Class HttpClient
 *
 * @package Framework\Crawler
 */
class HttpClient
{
    /**
     * @var int
     */
    protected $id;

    /** @var bool */
    protected $debug;

    /** @var bool */
    protected $proxy;

    /**
     * @var string
     */
    protected $base_uri;

    /** @var array */
    protected $login;

    /**
     * @var string
     */
    protected $dir;

    /** @var Client */
    private $_client;

    /** @var string */
    private $_url;

    /**
     * @var FileCookieJar
     */
    private $_cookieJar;

    /**
     * HttpClient constructor.
     *
     * @param array  $config
     * @param string $dir
     */
    public function __construct(array $config, string $dir = null)
    {
        foreach ($config as $key => $value) {
            if (\property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        $this->dir = $dir;
        $this->_client = $this->getClient();
        $this->_cookieJar = $this->getCookieJar();
    }

    /**
     * Идентификатор сессии
     *
     * @param int $id
     * @return HttpClient
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param       $method
     * @param       $url
     * @param array $params
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function request(string $method, string $url, array $params = []): ResponseInterface
    {
        try {
            $params = ArrayHelper::merge($params, [
                'on_stats' => static function (TransferStats $stats) use (&$full_url) {
                    $full_url = $stats->getEffectiveUri();
                },
                'cookies' => $this->_cookieJar,
                'debug' => $this->debug,
            ]);

            $response = $this->_client->request($method, $url, $params);

            $this->_url = (string)$full_url;

        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function getEffectiveUrl(): string
    {
        return $this->_url;
    }

    /**
     * @return Client
     */
    protected function getClient(): Client
    {
        if (!$this->_client) {
            $this->_client = new Client([
                'base_uri' => $this->base_uri,
                'proxy' => $this->loadProxy(),
                'headers' => [
                    'User-Agent' => UAgent::random(),
                ],
            ]);
        }

        return $this->_client;
    }

    /**
     * @return CookieJarInterface|null
     */
    protected function getCookieJar(): ?CookieJarInterface
    {
        if (!$this->_cookieJar && $this->login) {

            $this->_cookieJar = new FileCookieJar($this->cookieFile(), true);

            $maxExpired = 0;

            /** @var SetCookie $cookie */
            foreach ($this->_cookieJar as $cookie) {
                if ($maxExpired < $cookie->getExpires()) {
                    $maxExpired = $cookie->getExpires();
                }
            }

            if ($maxExpired !== 0 && $maxExpired < \time()) {
                $this->login();
            }
        }

        return $this->_cookieJar;
    }

    /**
     * Динамический логин
     *
     * @param array $data
     * @return HttpClient
     */
    public function setLoginFormParams(array $data): self
    {
        $this->login['form_params'] = $data;

        return $this;
    }

    public function login(): void
    {
        $this->_cookieJar = new FileCookieJar($this->cookieFile(), true);

        $this->request('POST', $this->login['url'], [
            'form_params' => $this->login['form_params'],
            'debug' => $this->debug,
            'proxy' => $this->loadProxy(),
        ]);
    }

    /**
     * @return string
     */
    protected function cookieFile(): string
    {
        $file = \md5($this->base_uri . $this->id);

        return "{$this->dir}/$file.txt";
    }

    protected function loadProxy(): ?string
    {
        if (\is_array($this->proxy)) {
            $key = \random_int(0, \count($this->proxy) - 1);

            return $this->proxy[$key];
        }

        return null;
    }
}
