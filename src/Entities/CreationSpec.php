<?php

namespace Snapchat\Entities;


class CreationSpec extends SnapchatEntity
{
    const TYPE_BALANCE = 'BALANCE';
    const TYPE_SIMILARITY = 'SIMILARITY';
    const TYPE_REACH = 'REACH';

    protected static $types = [self::TYPE_BALANCE, self::TYPE_SIMILARITY, self::TYPE_REACH];
    protected $requiredFields = ['country', 'seed_segment_id'];

    public $country;
    public $seed_segment_id;
    public $type = self::TYPE_BALANCE;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    protected function validateData()
    {
        parent::validateData();
        $this->validateInArray('type', 'types');

    }
}