<?php
declare(strict_types=1);

namespace app;

use app\base\Canvas;

class Wall
{
    public $initX;
    public $initY;

    public const SIZE_X = 10;
    public const SIZE_Y = 10;

    /**
     * Wall constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        // todo check if coordinates empty
        $this->initX = random_int(Canvas::CANVAS_START, Canvas::CANVAS_SIZE);
        $this->initY = random_int(Canvas::CANVAS_START, Canvas::CANVAS_SIZE);
    }
}