<?php

namespace Snapchat\Entities;


class Campaign extends SnapchatEntity
{

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_PAUSED = 'PAUSED';

    protected static $statuses = [self::STATUS_ACTIVE, self::STATUS_PAUSED];

    protected $requiredFields = [
        'ad_account_id',
        'name',
        'start_time',
        'status'
    ];

    public $id;
    public $ad_account_id;
    public $daily_budget_micro;
    public $end_time;
    public $name;
    public $start_time;
    public $status = self::STATUS_PAUSED;
    public $lifetime_spend_cap_micro;
    public $measurement_spec;

    public function __construct($data)
    {
        parent::__construct($data);
        $this->status = in_array($data['status'], self::$statuses) ? $data['status'] : self::STATUS_PAUSED;
    }

    protected function validateData()
    {
        parent::validateData();
        $this->validateInArray('status', 'statuses');
    }

}