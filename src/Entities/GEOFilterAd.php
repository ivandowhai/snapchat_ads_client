<?php

namespace Snapchat\Entities;


class GEOFilterAd extends Ad
{
    const TYPE_GEO_FILTER = 'GEO_FILTER';

    public $status = self::STATUS_ACTIVE;
    public $type = self::TYPE_GEO_FILTER;

    protected $requiredFields = [
        'ad_squad_id',
        'name',
        'creative_id'
    ];

}