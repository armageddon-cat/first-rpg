<?php
declare(strict_types=1);

namespace app\exceptions;

class InvalidGuidException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Invalid id');
    }
}
