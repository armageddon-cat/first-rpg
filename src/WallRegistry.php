<?php
declare(strict_types=1);

namespace app;

use app\base\AbstractRegistry;

class WallRegistry extends AbstractRegistry
{
    public function __construct(array $array = [], int $flags = 0)
    {
        parent::__construct($array, $flags);
    }
}