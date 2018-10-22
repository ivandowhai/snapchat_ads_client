<?php


namespace Snapchat;


class Token
{
    private $access_token;
    private $refresh_token;
    private $token_type;
    private $expires_in;


    public function __construct(array $params)
    {
        $this->access_token = $params['access_token'];
        $this->refresh_token = $params['refresh_token'];
        $this->token_type = $params['token_type'];
        $this->expires_in = $params['expires_in'];
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @return mixed
     */
    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    /**
     * @return mixed
     */
    public function getTokenType()
    {
        return $this->token_type;
    }
    /**
     * @return mixed
     */
    public function getExpiresIn()
    {
        return $this->expires_in;
    }

}