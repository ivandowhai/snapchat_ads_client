<?php

namespace Snapchat;

use GuzzleHttp\Client;

class HTTPClient implements HTTPClientInterface
{

    /**
     * @var Token
     */
    private $token;

    /**
     * @var Config
     */
    private $config;

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
     * @param Token $token
     * @param Config $config
     */
    public function __construct(Token $token, Config $config)
    {
        try {
            $this->httpClient = new Client();
            $this->config = $config;
            $this->token = $token;
            $this->makeHeaders();
        } catch (\Exception $exception) {
//            self::logErrors($exception);
        }
    }

    /**
     * @param array $params
     * @return Token
     * @throws \Exception
     */
    public function getToken(array $params): Token
    {
        $result = $this->httpClient->post('https://accounts.snapchat.com/login/oauth2/access_token', [
            'body' => $params,
            'headers' => ['Content-Type' => 'application/json']
        ]);

        if (isset($result['access_token'])) {
            return new Token(json_decode($result->getBody()->getContents(), true));
        }

        throw new \Exception('Not access token');
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
     * @param string $url
     * @return string
     */
    private function makeURI(string $url): string
    {
        return $this->config->getApiDomain() . DIRECTORY_SEPARATOR . $this->config->getApiVersion() . DIRECTORY_SEPARATOR . $url;
    }
}