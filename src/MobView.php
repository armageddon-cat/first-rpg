<?php
declare(strict_types=1);

namespace app;

class MobView
{
    public $src;

    public function __construct($direction)
    {
        if ($direction === Canvas::CODE_RIGHT_ARROW) {
            $this->src = 'src/img/hallview_full_right.png';
        }

        if ($direction === Canvas::CODE_LEFT_ARROW) {
            $this->src = 'src/img/hallview_full_left.png';
        }
    }
}