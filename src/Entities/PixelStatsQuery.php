<?php

namespace Snapchat\Entities;


class PixelStatsQuery extends SnapchatEntity
{
    const GRANULARITY_DAY = 'DAY';
    const GRANULARITY_HOUR = 'HOUR';

    const FIELD_EVENT_TYPE = 'EVENT_TYPE';
    const FIELD_OS_TYPE = 'OS_TYPE';
    const FIELD_BROWSER_TYPE = 'BROWSER_TYPE';

    protected static $granularities = [self::GRANULARITY_DAY, self::GRANULARITY_HOUR];
    private static $fieldsAvailable = [self::FIELD_EVENT_TYPE, self::FIELD_OS_TYPE, self::FIELD_BROWSER_TYPE];

    public $start_time;
    public $end_time;
    public $granularity;
    public $domain;
    public $fields;

    protected $requiredFields = ['start_time', 'end_time', 'granularity', 'domain', 'fields'];


    /**
     * PixelStatsQuery constructor.
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
        $this->validateInArray('granularity', 'granularities');

        if (array_key_exists('fields', $this->data)) {
            $this->validateFields($this->data['fields']);
        }
    }

    /**
     * @param array $fields
     */
    private function validateFields(array $fields): void
    {
        $invalidFields = [];
        foreach ($fields as $field) {
            if (!in_array($field, self::$fieldsAvailable)) {
                $invalidFields[] = $field;
            }
        }
        if (!empty($invalidFields)) {
            $this->addError('Fields ' . implode(', ', $invalidFields) . ' is invalid');
        }
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return [
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'granularity' => $this->granularity,
            'domain' => $this->domain,
            'fields' => implode(',', $this->fields)
        ];
    }

}