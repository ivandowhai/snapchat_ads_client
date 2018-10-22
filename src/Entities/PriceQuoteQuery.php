<?php

namespace Snapchat\Entities;


class PriceQuoteQuery extends SnapchatEntity
{
    public $start_time;
    public $end_time;
    public $targeting;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    protected function validateData(array $data)
    {
        parent::validateData();
        $this->validateInstanseof('targeting', 'Targeting');
    }
}