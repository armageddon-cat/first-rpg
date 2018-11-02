<?php
declare(strict_types=1);

namespace app\exceptions;

class InvalidDateTimeFormatException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Invalid Date Time Format');
    }
}
