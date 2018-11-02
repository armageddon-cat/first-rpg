<?php
declare(strict_types=1);

namespace app\validators;

class UnixTimeStampFloatValidator implements ValidatorInterface
{
    /**
     * Validates string like 1481039154.9632
     */
    public static function validate($value): bool
    {
        return preg_match('/^\d{10}\.\d{1,4}$/', (string)$value) === 1;
    }
}
