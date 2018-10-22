<?php

namespace Snapchat\Entities;


class GEOFilterMedia extends Media
{
    protected $imageExtensions = ['png'];
    protected $imageMaxSize = 307200;

    private static $types = [self::TYPE_IMAGE];

    public $type = self::TYPE_IMAGE;

    private static $dimensions = [
        ['width' => 1080, 'height' => 1920],
        ['width' => 1080, 'height' => 2340]
    ];

    // TODO: validate dimensions


}