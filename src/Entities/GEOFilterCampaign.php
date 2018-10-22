<?php

namespace Snapchat\Entities;


class GEOFilterCampaign extends Campaign
{

    public $status = self::STATUS_ACTIVE;

    private $readOnly = ['status', 'daily_budget_micro'];

    public function __construct($data)
    {
        SnapchatEntity::__construct($data);
    }
}