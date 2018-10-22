<?php

namespace Snapchat\Entities;


class Lookalike extends SnapchatEntity
{
    const SOURCE_TYPE_LOOKALIKE = 'LOOKALIKE';
    const MAX_RETENTION_IN_DAYS = 180;


    public $ad_account_id;
    public $creation_spec;
    public $description;
    public $name;
    public $retention_in_days;
    public $source_type;

    protected $requiredFields = ['ad_account_id', 'creation_spec', 'name', 'retention_in_days', 'source_type'];

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->source_type = self::SOURCE_TYPE_LOOKALIKE;
    }

    protected function validateData()
    {
        parent::validateData();
        $this->validateInstanseof('creation_spec', 'CreationSpec');
        $this->validateIsInt('retention_in_days');

        if (array_key_exists('retention_in_days', $this->data) && $this->data['retention_in_days'] > self::MAX_RETENTION_IN_DAYS) {
            $this->addError($this->data['retention_in_days'] . ' must be less or equal ' . self::MAX_RETENTION_IN_DAYS);
        }
    }
}