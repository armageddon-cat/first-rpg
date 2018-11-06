<?php
declare(strict_types=1);

namespace app;

class MobView extends View
{
    public $src;
    public $mobVisibleBlocks = [];

    public function __construct(int $viewType)
    {
        if ($viewType === View::FRONT_WALL_VIEW) {
            $this->src = 'src/img/mob3dFull.png';
        }
        if ($viewType === View::HALF_VIEW) {
            $this->src = 'src/img/mob3dMedium.png';
        }
        if ($viewType === View::END_VIEW) {
            $this->src = 'src/img/mob3dSmall.png';
        }
    }
}