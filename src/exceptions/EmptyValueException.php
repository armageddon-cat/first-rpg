<?php
declare(strict_types=1);

namespace app\exceptions;

class EmptyValueException extends \InvalidArgumentException
{
    public function __construct(string $valueName)
    {
        parent::__construct('empty ' . $valueName);
    }
}
