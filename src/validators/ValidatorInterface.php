<?php
declare(strict_types=1);

namespace app\validators;

interface ValidatorInterface
{
    public static function validate($value): bool;
}
