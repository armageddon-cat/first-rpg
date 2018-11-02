<?php
declare(strict_types=1);

namespace app\exceptions;

class EmptyPayLoadException extends EmptyValueException
{
    public function __construct()
    {
        parent::__construct('payload');
    }
}
