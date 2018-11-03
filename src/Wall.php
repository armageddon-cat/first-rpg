<?php
declare(strict_types=1);

namespace app;

use app\base\Canvas;

class Wall
{
    public $initX;
    public $initY;

    public const SIZE_X = 20;
    public const SIZE_Y = 20;
    public const OFFSET = 1;

    /**
     * Wall constructor.
     * @throws \Exception
     */
    public function __construct($x, $y)
    {
        // todo check if coordinates empty
        $this->initX = $x;
        $this->initY = $y;
    }
}