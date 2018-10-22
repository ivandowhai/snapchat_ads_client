<?php

namespace Snapchat\Entities;


class Stats extends SnapchatEntity
{
    const TYPE_CAMPAIGN = 'CAMPAIGN';
    const TYPE_AD_SQUAD = 'AD_SQUAD';
    const TYPE_AD = 'AD';

    protected static $types = [
        self::TYPE_CAMPAIGN => 'campaigns',
        self::TYPE_AD_SQUAD => 'adsquads',
        self::TYPE_AD => 'ads'
    ];

    public $impressions;
    public $swipes;
    public $spend;
    public $quartile_1;
    public $quartile_2;
    public $quartile_3;
    public $view_completion;
    public $screen_time_millis;


    public function validateData()
    {
        parent::validateData();
    }

    public static function validateType($type) : bool
    {
        return array_key_exists($type, self::$types);
    }

    public static function getEndpoint($type) : string
    {
        return self::$types[$type];
    }
}