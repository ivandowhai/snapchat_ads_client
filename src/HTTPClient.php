<?php

namespace Snapchat;

use GuzzleHttp\Client;

class HTTPClient implements HTTPClientInterface
{

    const API_DOMAIN = 'https://adsapi.snapchat.com';

    const API_VERSION = 'v1';

    /**
     * @var Token
     */
    private $token;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var array
     */
    private $headersGet;

    /**
     * @var array
     */
    private $headersPost;

    /**
     * @var array
     */
    private $headersUpload;

    /**
     * HTTPClient constructor.
     * @param $params
     */
    public function __construct($params)
    {
        try {
            $this->httpClient = new Client();
            $this->token = $this->getToken($params);
            $this->makeHeaders();
        } catch (\Exception $exception) {
//            self::logErrors($exception);
        }
    }


    private function makeHeaders()
    {
        $this->headersGet = [
            'Authorization' => $this->token->getTokenType() . ' ' . $this->token->getAccessToken()
        ];
        $this->headersPost = [
            'Authorization' => $this->token->getTokenType() . ' ' . $this->token->getAccessToken(),
            'Content-Type' => 'application/json'
        ];
        $this->headersUpload = [
            'Authorization' => $this->token->getTokenType() . ' ' . $this->token->getAccessToken(),
            'content-type' => 'multipart/form-data'
        ];
    }

    /**
     * @param string $url
     * @param array $query
     * @return array
     */
    public function get(string $url, array $query = []): array
    {
        return json_decode($this->httpClient->get($this->makeURI($url), [
            'headers' => $this->headersGet,
            'query' => $query
        ]), true);
    }


    /**
     * @param string $url
     * @param array $body
     * @return array
     */
    public function post(string $url, array $body): array
    {
        return json_decode($this->httpClient->post($this->makeURI($url), [
            'headers' => $this->headersPost,
            'body' => $body
        ]), true);
    }

    /**
     * @param string $url
     * @param array $body
     * @return mixed
     */
    public function put(string $url, array $body): array
    {
        return json_decode($this->httpClient->put($this->makeURI($url), [
            'headers' => $this->headersPost,
            'body' => $body
        ]), true);
    }

    /**
     * @param string $url
     * @return array
     */
    public function delete(string $url): array
    {
        return json_decode($this->httpClient->get($this->makeURI($url), [
            'headers' => $this->headersGet
        ]), true);
    }

    /**
     * @param string $url
     * @param string $pathToFile
     * @param string $fileName
     * @return array
     */
    public function upload(string $url, string $pathToFile, string $fileName): array
    {
        return json_decode($this->httpClient->post($this->makeURI($url), [
            'headers' => $this->headersUpload,
            'multipart' => [
                'name' => time(),
                'contents' => file_get_contents($pathToFile),
                'filename' => $fileName
            ]
        ]), true);
    }

    /**
     * @param array $result
     * @return bool
     */
    public function checkResponse(array $result): bool
    {
        return $result['request_status'] === 'success' || $result['request_status'] === 'SUCCESS';
    }

    /**
     * @param array $params
     * @return Token
     * @throws \Exception
     */
    private function getToken(array $params): Token
    {
        $result = $this->post('https://accounts.snapchat.com/login/oauth2/access_token', $params);
        if (isset($result['access_token'])) {
            return new Token($result);
        }
        throw new \Exception('Not access token');
    }

    /**
     * @param string $url
     * @return string
     */
    private function makeURI(string $url): string
    {
        return self::API_DOMAIN . DIRECTORY_SEPARATOR . self::API_VERSION . DIRECTORY_SEPARATOR . $url;
    }
}