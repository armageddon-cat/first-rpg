<?php
declare(strict_types=1);

namespace app;

use app\base\Canvas;

class EndView extends View
{
    public $src;

    public function __construct(int $direction, int $nextDirection)
    {
        if ($nextDirection === WallGenerator::DIRECTION_RIGHT) {
            $this->src = 'src/img/hallview_end_right.png';
        }

        if ($nextDirection === WallGenerator::DIRECTION_LEFT) {
            $this->src = 'src/img/hallview_end_left.png';
        }

        if ($direction === WallGenerator::DIRECTION_RIGHT && $nextDirection === WallGenerator::DIRECTION_UP) {
            $this->src = 'src/img/hallview_end_left.png';
        }

        if ($direction === WallGenerator::DIRECTION_LEFT && $nextDirection === WallGenerator::DIRECTION_UP) {
            $this->src = 'src/img/hallview_end_right.png';
        }

    }
}