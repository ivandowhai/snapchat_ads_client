<?php

namespace Snapchat\Entities;


class Pixel extends SnapchatEntity
{
    public $id;
    public $updated_at;
    public $created_at;
    public $effective_status;
    public $name;
    public $ad_account_id;
    public $status;
    public $pixel_javascript;

    protected $requiredFields = ['id', 'name', 'ad_account_id'];

    /**
     * Pixel constructor.
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
    }
}