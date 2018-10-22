<?php

namespace Snapchat\Entities;


class Creative extends SnapchatEntity
{
    const HEADLINE_MAX_LENGTH = 34;

    const ACTION_BLANK = 'BLANK';
    const ACTION_INSTALL_NOW = 'INSTALL_NOW';
    const ACTION_WATCH = 'WATCH';
    const ACTION_VIEW_MORE = 'VIEW_MORE';

    const POSITION_OPTIMIZED = 'OPTIMIZED';
    const POSITION_MIDDLE = 'MIDDLE';
    const POSITION_TOP = 'TOP';
    const POSITION_BOTTOM = 'BOTTOM';

    const TYPE_SNAP_AD = 'SNAP_AD';
    const TYPE_APP_INSTALL = 'APP_INSTALL';
    const TYPE_LONGFORM_VIDEO = 'LONGFORM_VIDEO';
    const TYPE_WEB_VIEW = 'WEB_VIEW';
    const TYPE_DEEP_LINK = 'DEEP_LINK';

    protected static $top_snap_crop_positions = [
        self::POSITION_OPTIMIZED,
        self::POSITION_MIDDLE,
        self::POSITION_TOP,
        self::POSITION_BOTTOM
    ];

    protected static $actions = [
        self::ACTION_BLANK,
        self::ACTION_INSTALL_NOW,
        self::ACTION_WATCH,
        self::ACTION_VIEW_MORE
    ];

    protected static $types = [
        self::TYPE_SNAP_AD,
        self::TYPE_APP_INSTALL,
        self::TYPE_LONGFORM_VIDEO,
        self::TYPE_WEB_VIEW,
        self::TYPE_DEEP_LINK
    ];


    public $id;
    public $ad_account_id;
    public $brand_name;
    public $call_to_action;
    public $headline;
    public $shareable;
    public $name;
    public $top_snap_media_id;
    public $top_snap_crop_position;
    public $type;

    protected $requiredFields = [
        'ad_account_id',
        'brand_name',
        'headline',
        'name',
        'top_snap_media_id',
        'type'
    ];


    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    protected function validateData(): void
    {
        parent::validateData();
        $this->validateInArray('type', 'types');
        $this->validateInArray('call_to_action', 'actions');
        $this->validateInArray('top_snap_crop_position', 'top_snap_crop_positions');
        $this->validateIsBool('shareable');

        if (array_key_exists('headline', $this->data) && strlen($this->data['headline']) > self::HEADLINE_MAX_LENGTH) {
            $this->addError('Headline can`t be longest ' . self::HEADLINE_MAX_LENGTH . ' characters');
        }
    }

}