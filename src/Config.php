<?php

namespace Snapchat;

class Config
{
    /** @var string  */
    private $apiDomain;

    /** @var string  */
    private $apiVersion;

    public function __construct()
    {
        $config = json_decode(file_get_contents('../config.json'), true);
        $this->apiDomain = $config['apiDomain'] ?? 'https://adsapi.snapchat.com';
        $this->apiVersion = $config['apiVersion'] ?? 'v1';
    }

    /**
     * @return string
     */
    public function getApiDomain()
    {
        return $this->apiDomain;
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

}