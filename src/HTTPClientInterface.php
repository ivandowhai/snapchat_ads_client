<?php


namespace Snapchat;


interface HTTPClientInterface
{
    /**
     * @param string $url
     * @param array $query
     * @return array
     */
    public function get(string $url, array $query = []): array;

    /**
     * @param string $url
     * @param array $body
     * @return array
     */
    public function post(string $url, array $body): array;

    /**
     * @param string $url
     * @param array $body
     * @return array
     */
    public function put(string $url, array $body): array;

    /**
     * @param string $url
     * @return array
     */
    public function delete(string $url): array;

    /**
     * @param string $url
     * @param string $pathToFile
     * @param string $fileName
     * @return array
     */
    public function upload(string $url, string $pathToFile, string $fileName): array;

    /**
     * @param array $response
     * @return bool
     */
    public function checkResponse(array $response): bool;
}