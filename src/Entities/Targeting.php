<?php

namespace Snapchat\Entities;


class Targeting extends SnapchatEntity
{
//    https://developers.snapchat.com/api/docs/?shell#example-targeting-specs

    const GENDER_MALE = 'MALE';
    const GENDER_FEMALE = 'FEMALE';
    const GENDER_OTHER = 'OTHER';

    const OS_I_OS = 'iOS';
    const OS_ANDROID = 'ANDROID';

    protected static $oses = [self::OS_I_OS, self::OS_ANDROID];

    protected static $genders = [self::GENDER_MALE, self::GENDER_FEMALE, self::GENDER_OTHER];

    protected $validateMethods = [
        'geos' => 'validateGeos',
        'emographics' => 'validateDemographics',
        'regulated_content' => 'validateRegulatedContent',
        'devices' => 'validateDevices'
    ];

    const GEO_POSTAL_CODE = 'postal_code';
    const GEO_COUNTRY_CODE = 'country_code';
    const DEVICE_OS_TYPE = 'os_type';

    private static $parts = [
        'demographics' => ['age_group', 'gender', 'languages', 'advanced_demographics'],
        'device' => ['connection_type', 'os_type', 'os_version', 'carrier', 'marketing_name'],
        'geo' => ['country', 'region', 'metro', 'postal_code'],
        'interests' => ['scls', 'dlxs', 'dlxc', 'dlxp'],
        'location' => ['categories_loi']
    ];

    private static $difficultParts = [
        'os_version' => 'os_type',
        'region' => 'country_code',
        'metro' => 'country_code',
        'postal_code' => 'country_code'
    ];

    public $id;

    public $demographics;
    public $devices;
    public $geos;
    public $interests;
    public $regulated_content = false;
    public $segments;
    protected $requiredFields = ['geos'];


    /**
     * Targeting constructor.
     * @param array $data
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    /**
     * @return array
     */
    public static function getParts(): array
    {
        return self::$parts;
    }

    /**
     * @return array
     */
    public static function getDifficultParts() : array
    {
        return self::$difficultParts;
    }

    /**
     *
     */
    protected function validateData(): void
    {
        parent::validateData();

        foreach ($this->validateMethods as $key => $method) {
            if (isset($data[$key])) {
                $this->$method($this->data[$key]);
            }
        }
    }

    // TODO: other fields validation

    /**
     * @param $geos
     * @return array
     */
    private function validateGeos(array $geos): array
    {
        if (!is_array($geos)) {
            return 'geos must be an array';
        }

        if (!array_key_exists('country_code', $geos)) {
            return 'country_code is required';
        }
        return [];
    }

    // TODO: validate ages, languages, DLX
    private function validateDemographics($demographics)
    {
        $fields = ['age_groups', 'languages', 'advanced_demographics'];

        if (!is_array($demographics)) {
            $this->addError('demographics must be an array');
            return;
        }

        foreach ($fields as $field) {
            if (isset($demographics[$field]) && !is_array($demographics[$field])) {
                $this->addError($field . ' must be an array');
            }
        }
        if (!in_array($demographics['gender'], self::$genders)) {
            $this->addError('Invalid gender');
        }
    }

    private function validateRegulatedContent($regulated_content)
    {
        if (!is_bool($regulated_content)) {
            $this->addError('regulated_content must be bool');
        }
    }

    private function validateDevices($devices)
    {
        $fields = ['connection_type', 'os_type', 'os_version', 'carrier', 'marketing_name'];

        if (!is_array($devices)) {
            $this->addError('devices must be an array');
            return;
        }

        foreach ($fields as $field) {
            if (isset($demographics[$field]) && !is_array($devices[$field])) {
                $this->addError($field . ' must be an array');
            }
        }

        if (!in_array($devices['os_type'], self::$oses)) {
            $this->addError('Invalid OS');
        }
    }


    /**
     * @param string $part
     * @param string $subpart
     * @param string $advancedOption
     * @throws \Exception
     */
    public static function validateTargetingLinkParts(string $part, string $subpart, string $advancedOption)
    {
        if (!array_key_exists($part, self::$parts)) {
            throw new \Exception("$part is wrong part of endpoint");
        }
        if (!in_array($subpart, self::$parts[$part])) {
            throw new \Exception("$subpart is wrong part of endpoint");
        }
        if (array_key_exists($subpart, self::$difficultParts) && $advancedOption === '') {
            throw new \Exception('$advancedOption is required for this endpoint');
        }
    }

}