<?php

namespace Snapchat\Entities;


class StatsQuery extends SnapchatEntity
{
    const GRANULARITY_TOTAL = 'TOTAL';
    const GRANULARITY_DAY = 'DAY';
    const GRANULARITY_HOUR = 'HOUR';
    const GRANULARITY_LIFETIME = 'LIFETIME';

    const DIMENSION_GEO = 'GEO';
    const DIMENSION_DEMO = 'DEMO';
    const DIMENSION_INTEREST = 'INTEREST';
    const DIMENSION_DEVICE = 'DEVICE';

    const PIVOT_COUNTRY = 'country';
    const PIVOT_REGION = 'region';
    const PIVOT_DMA = 'dma';
    const PIVOT_GENDER = 'gender';
    const PIVOT_AGE_BUCKET = 'age_bucket';
    const PIVOT_INTEREST_CATEGORY_ID = 'interest_category_id';
    const PIVOT_INTEREST_CATEGORY_NAME = 'interest_category_name';
    const PIVOT_OPERATING_SYSTEM = 'operating_system';
    const PIVOT_MAKE = 'make';
    const PIVOT_MODEL = 'model';

    const SWIPE_UP_1_DAY = '1_DAY';
    const SWIPE_UP_7_DAY = '7_DAY';
    const SWIPE_UP_28_DAY = '28_DAY';

    const VIEW_1_HOUR = '1_HOUR';
    const VIEW_3_HOUR = '3_HOUR';
    const VIEW_6_HOUR = '6_HOUR';
    const VIEW_1_DAY = '1_DAY';
    const VIEW_7_DAY = '7_DAY';
    const VIEW_28_DAY = '28_DAY';

    protected static $granularities = [
        self::GRANULARITY_TOTAL,
        self::GRANULARITY_DAY,
        self::GRANULARITY_HOUR,
        self::GRANULARITY_LIFETIME
    ];

    protected static $dimensions = [
        self::DIMENSION_GEO,
        self::DIMENSION_DEMO,
        self::DIMENSION_INTEREST,
        self::DIMENSION_DEVICE
    ];

    protected static $pivots = [
        self::PIVOT_COUNTRY,
        self::PIVOT_REGION,
        self::PIVOT_DMA,
        self::PIVOT_AGE_BUCKET,
        self::PIVOT_INTEREST_CATEGORY_ID,
        self::PIVOT_INTEREST_CATEGORY_NAME,
        self::PIVOT_OPERATING_SYSTEM,
        self::PIVOT_MAKE,
        self::PIVOT_MODEL
    ];

    protected static $swipe_up_attributions = [
        self::SWIPE_UP_1_DAY,
        self::SWIPE_UP_7_DAY,
        self::SWIPE_UP_28_DAY
    ];

    protected static $view_attribution = [
        self::VIEW_1_HOUR,
        self::VIEW_3_HOUR,
        self::VIEW_6_HOUR,
        self::VIEW_1_DAY,
        self::VIEW_7_DAY,
        self::VIEW_28_DAY,
    ];

    public $breakdown;
    public $fields;
    public $end_time;
    public $start_time;
    public $granularity;
    public $test = false;
    public $dimension;
    public $pivot;
    public $swipe_up_attribution_window = self::SWIPE_UP_28_DAY;
    public $view_attribution_window = self::VIEW_1_DAY;

    protected $requiredFields = ['granularity'];


    public function __construct(array $data)
    {
        parent::__construct($data);
        if (!$this->validateTimeBorders()) {
            throw new \Exception('Error! start_time and end_time is required for chosen granularity');
        }
    }

    protected function validateData()
    {
        parent::validateData();
        $this->validateInArray('granularity', 'granularities');
        $this->validateInArray('dimension', 'dimensions');
        $this->validateInArray('pivot', 'pivots');
        $this->validateInArray('swipe_up_attribution_window', 'swipe_up_attributions');
        $this->validateInArray('view_attribution_window', 'view_attribution');
        $this->validateIsBool('test');
    }

    private function validateTimeBorders()
    {
        if ($this->granularity === self::GRANULARITY_HOUR || $this->granularity === self::GRANULARITY_DAY) {
            if (!$this->start_time || !$this->end_time) {
                return false;
            }
        }
        return true;
    }

}