<?php

namespace Snapchat\Entities;


class Media extends SnapchatEntity
{

    protected $imageExtensions = ['png', 'jpg', 'jpeg'];
    protected $imageMaxSize = 5242880;

    const MEDIA_STATUS_PENDING_UPLOAD = 'PENDING_UPLOAD';
    const MEDIA_STATUS_READY = 'READY';

    const TYPE_VIDEO = 'VIDEO';
    const TYPE_IMAGE = 'IMAGE';

    protected static $types = [
        self::TYPE_VIDEO,
        self::TYPE_IMAGE
    ];

    public $id;
    public $ad_account_id;
    public $download_link;
    public $media_status;
    public $name;
    public $type;

    protected $readOnly = ['download_link', 'media_status'];
    protected $requiredFields = ['ad_account_id', 'name', 'type'];

    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    protected function validateData()
    {
        parent::validateData();
        $this->validateInArray('type', 'types');
    }

    public function validateImageForUpload($pathToFile) : array
    {
        $errors = [];

        if ($this->type !== static::TYPE_IMAGE) {
            $errors[] = 'Wrong media type';
        }

        if (!in_array(pathinfo($pathToFile)['extension'], $this->imageExtensions)) {
            $errors[] = 'Wrong file type';
        }

        if (filesize($pathToFile) > $this->imageMaxSize) {
            $errors[] = 'Max file size is ' . $this->imageMaxSize;
        }

        return $errors;
    }
}