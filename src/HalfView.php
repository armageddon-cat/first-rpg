<?php
declare(strict_types=1);

namespace app;

use app\base\Canvas;

class HalfView extends View
{
    public $src;

    public function __construct($direction)
    {
        if ($direction === WallGenerator::DIRECTION_RIGHT) {
            $this->src = 'src/img/hallview_half_right.png';
        }

        if ($direction === WallGenerator::DIRECTION_LEFT) {
            $this->src = 'src/img/hallview_half_left.png';
        }
    }
}