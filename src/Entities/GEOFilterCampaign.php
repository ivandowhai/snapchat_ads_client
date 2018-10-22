<?php

namespace Snapchat\Entities;


class GEOFilterCampaign extends Campaign
{

    public $status = self::STATUS_ACTIVE;

    private $readOnly = ['status', 'daily_budget_micro'];

    /**
     * GEOFilterCampaign constructor.
     * @param array $data
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        SnapchatEntity::__construct($data);
    }
}