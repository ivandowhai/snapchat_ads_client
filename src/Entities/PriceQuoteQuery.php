<?php

namespace Snapchat\Entities;


class PriceQuoteQuery extends SnapchatEntity
{
    public $start_time;
    public $end_time;
    public $targeting;

    /**
     * PriceQuoteQuery constructor.
     * @param array $data
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    protected function validateData(): void
    {
        parent::validateData();
        $this->validateInstanseof('targeting', 'Targeting');
    }
}