<?php

namespace Snapchat\Entities;


class GEOFilterAdSquad extends AdSquad
{
    const BILLING_EVENT_FIXED_PRICE = 'FIXED_PRICE';

    const PLACEMENT_CAMERA = 'CAMERA';

    const TYPE_GEO_FILTER = 'GEO_FILTER';

    public $billing_event = self::BILLING_EVENT_FIXED_PRICE;
    public $optimization_goal = self::OPTIMISATION_GOAL_IMPRESSIONS;
    public $placement = self::PLACEMENT_CAMERA;
    public $status = self::STATUS_ACTIVE;
    public $type = self::TYPE_GEO_FILTER;
    public $bid_micro;
    public $daily_budget_micro;

    protected $requiredFields = ['campaign_id', 'name', 'targeting',];

    private static $readOnly = ['billing_event', 'optimization_goal', 'placement', 'status', 'type', 'bid_micro', 'daily_budget_micro'];

    public function __construct($data)
    {
        SnapchatEntity::__construct($data);
    }

}