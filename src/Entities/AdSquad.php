<?php

namespace Snapchat\Entities;


class AdSquad extends SnapchatEntity
{
    const DAILY_BUDGET_MICRO_MIN = 50000000;

    const BILLING_EVENT_IMPRESSION = 'IMPRESSION';
    const TYPE_SNAP_ADS = 'SNAP_ADS';

    const OPTIMISATION_GOAL_IMPRESSIONS = 'IMPRESSIONS';
    const OPTIMISATION_GOAL_SWIPES = 'SWIPES';
    const OPTIMISATION_GOAL_APP_INSTALLS = 'APP_INSTALLS';
    const OPTIMISATION_GOAL_VIDEO_VIEWS = 'VIDEO_VIEWS';

    const PLACEMENT_SNAP_ADS = 'SNAP_ADS';
    const PLACEMENT_CONTENT = 'CONTENT';
    const PLACEMENT_USER_STORIES = 'USER_STORIES';

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_PAUSED = 'PAUSED';

    const CONTENT_TYPE_NEWS = 'NEWS';
    const CONTENT_TYPE_ENTERTAINMENT = 'ENTERTAINMENT';
    const CONTENT_TYPE_SCIENCE_TECHNOLOGY = 'SCIENCE_TECHNOLOGY';
    const CONTENT_TYPE_BEAUTY_FASHION = 'BEAUTY_FASHION';
    const CONTENT_TYPE_MENS_LIFESTYLE = 'MENS_LIFESTYLE';
    const CONTENT_TYPE_WOMENS_LIFESTYLE = 'WOMENS_LIFESTYLE';
    const CONTENT_TYPE_GENERAL_LIFESTYLE = 'GENERAL_LIFESTYLE';
    const CONTENT_TYPE_FOOD = 'FOOD';
    const CONTENT_TYPE_SPORTS = 'SPORTS';
    const CONTENT_TYPE_YOUNG_BOLD = 'YOUNG_BOLD';


    protected static $optimisation_goals = [
        self::OPTIMISATION_GOAL_IMPRESSIONS,
        self::OPTIMISATION_GOAL_SWIPES,
        self::OPTIMISATION_GOAL_APP_INSTALLS,
        self::OPTIMISATION_GOAL_VIDEO_VIEWS
    ];

    protected static $placements = [
        self::PLACEMENT_SNAP_ADS,
        self::PLACEMENT_CONTENT,
        self::PLACEMENT_USER_STORIES
    ];

    protected static $statuses = [self::STATUS_ACTIVE, self::STATUS_PAUSED];

    protected static $content_types = [
        self::CONTENT_TYPE_NEWS,
        self::CONTENT_TYPE_ENTERTAINMENT,
        self::CONTENT_TYPE_SCIENCE_TECHNOLOGY,
        self::CONTENT_TYPE_BEAUTY_FASHION,
        self::CONTENT_TYPE_MENS_LIFESTYLE,
        self::CONTENT_TYPE_WOMENS_LIFESTYLE,
        self::CONTENT_TYPE_GENERAL_LIFESTYLE,
        self::CONTENT_TYPE_FOOD,
        self::CONTENT_TYPE_SPORTS,
        self::CONTENT_TYPE_YOUNG_BOLD
    ];

    protected $requiredFields = [
        'campaign_id',
        'bid_micro',
        'billing_event',
        'daily_budget_micro',
        'name',
        'optimization_goal',
        'placement',
        'status',
        'targeting',
        'type'
    ];

    public $id;
    public $campaign_id;
    public $bid_micro;
    public $billing_event;
    public $daily_budget_micro;
    public $end_time;
    public $name;
    public $optimization_goal;
    public $placement;
    public $start_time;
    public $status;
    public $targeting;
    public $type;
    public $included_content_types;
    public $excluded_content_types;
    public $cap_and_exclusion_config;
    public $lifetime_budget_micro;
    public $ad_scheduling_config;
    public $pixel_id;


    public function __construct($data)
    {
        parent::__construct($data);
        $this->billing_event = self::BILLING_EVENT_IMPRESSION;
        $this->type = self::TYPE_SNAP_ADS;
    }

    protected function validateData()
    {
        parent::validateData();
        $this->validateInArray('optimization_goal', 'optimisation_goals');
        $this->validateInArray('placement', 'placements');
        $this->validateInArray('status', 'statuses');
        $this->validateInstanseof('targeting', 'Targeting');

        if ($this->data['daily_budget_micro'] < self::DAILY_BUDGET_MICRO_MIN) {
            $this->addError('daily_budget_micro minimum value ' . self::DAILY_BUDGET_MICRO_MIN);
        }

        if (array_key_exists('included_content_types', $this->data) && $invalidTypesError = $this->validateContentTypes($this->data['included_content_types'])) {
            $this->addError($invalidTypesError);
        }

        if (array_key_exists('excluded_content_types', $this->data) && $invalidTypesError = $this->validateContentTypes($this->data['excluded_content_types'])) {
            $this->addError($invalidTypesError);
        }
    }


    private function validateContentTypes($types)
    {
        $invalidTypes = [];
        foreach ($types as $type) {
            if (!in_array($type, self::$content_types)) {
                $invalidTypes[] = $type;
            }
        }
        if (!empty($invalidTypes)) {
            return 'Types ' . implode(', ', $invalidTypes) . ' is invalid';
        }

        return false;
    }

}