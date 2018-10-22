<?php

namespace Snapchat\Entities;


class Ad extends SnapchatEntity
{

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_PAUSED = 'PAUSED';

    const TYPE_SNAP_AD = 'SNAP_AD';
    const TYPE_LONGFORM_VIDEO = 'LONGFORM_VIDEO';
    const TYPE_APP_INSTALL = 'APP_INSTALL';
    const TYPE_REMOTE_WEBPAGE = 'REMOTE_WEBPAGE';
    const TYPE_DEEP_LINK = 'DEEP_LINK';

    const REVIEW_STATUS_PENDING = 'PENDING';
    const REVIEW_STATUS_APPROVED = 'APPROVED';
    const REVIEW_STATUS_REJECTED = 'REJECTED';

    protected static $statuses = [self::STATUS_ACTIVE, self::STATUS_PAUSED];

    protected static $types = [
        self::TYPE_SNAP_AD,
        self::TYPE_LONGFORM_VIDEO,
        self::TYPE_APP_INSTALL,
        self::TYPE_REMOTE_WEBPAGE,
        self::TYPE_DEEP_LINK
    ];


    public $id;
    public $ad_squad_id;
    public $creative_id;
    public $name;
    public $review_status;
    public $review_status_reason;
    public $status;
    public $type;

    protected $readOnly = ['review_status', 'review_status_reason'];

    protected $requiredFields = [
        'ad_squad_id',
        'name',
        'creative_id',
        'status',
        'type'
    ];

    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    protected function validateData()
    {
        parent::validateData();
        $this->validateInArray('status', 'statuses');
        $this->validateInArray('type', 'types');
    }
}