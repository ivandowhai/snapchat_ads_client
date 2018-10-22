<?php

namespace Snapchat\Entities;


abstract class SnapchatEntity
{

    protected $readOnly = [];
    protected $requiredFields = [];
    protected $errors = [];
    protected $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->validateData();
        if (!empty($this->errors)) {
            throw new \Exception('Fail to create! ' . implode(', ', $this->errors));
        }
        $this->setFields();
    }

    public function addError($error)
    {
        $this->errors[] = $error;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setFields()
    {
        foreach ($this->data as $key => $value) {
            if (property_exists(static::class, $key) && !empty($value) && $value != '' && $value !== null) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this);
    }

    protected function validateData()
    {
        foreach ($this->requiredFields as $field) {
            if (!array_key_exists($field, $this->data) || (array_key_exists($field, $this->data) && $this->data[$field] == '')) {
                $this->addError($field . ' field is required');
            }
        }
    }

    protected function validateInArray(string $field, string $array)
    {
        if (array_key_exists('status', $this->data)
            && !in_array($this->data[$field], static::$$array)) {
            $this->addError("Wrong $field");
        }
    }

    protected function validateInstanseof(string $field, string $className)
    {
        if (array_key_exists($field, $this->data)
            && !($this->data[$field] instanceof $className)) {
            $this->addError("Wrong $field");
        }
    }

    protected function validateIsBool(string $field)
    {
        if (array_key_exists($field, $this->data) && !is_bool($this->data[$field])) {
            $this->addError("$field must be bool");
        }
    }

    protected function validateIsInt(string $field)
    {
        if (array_key_exists($field, $this->data) && !is_int($this->data[$field])) {
            $this->addError("$field must be integer");
        }
    }
}