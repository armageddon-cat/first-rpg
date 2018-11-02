<?php
declare(strict_types=1);

namespace app\validators;

class GuidValidator implements ValidatorInterface
{
    public const GUID_PATTERN = '/^([0-9abcdef]{8}-[0-9abcdef]{4}-[0-9abcdef]{4}-[0-9abcdef]{4}-[0-9abcdef]{12})$/';

    public static function validate($ref): bool
    {
        return preg_match(self::GUID_PATTERN, $ref) === 1;
    }
}
