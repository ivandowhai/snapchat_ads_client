<?php

namespace Snapchat\Entities;


class GEOFilterCreative extends Creative
{
    const BRAND_NAME_MAX_LENGTH = 25;

    const TYPE_GEO_FILTER = 'GEO_FILTER';

    public $type = self::TYPE_GEO_FILTER;

    /**
     * GEOFilterCreative constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}