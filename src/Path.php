<?php
declare(strict_types=1);

namespace app;

class Path
{
    public $freeBlock = [];

    public function addFreeBlocks($freeBlock)
    {
        $this->freeBlock[] = $freeBlock;
    }
}