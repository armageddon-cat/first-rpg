<?php
declare(strict_types=1);

namespace app;

use app\base\Canvas;

class Move
{
    public $direction;
    public $x;
    public $y;

    public function __construct(ClientMessageContainer $container)
    {
        $this->direction = $container->getData()->direction;
    }

    public function isAllowed(): bool
    {
        switch ($this->direction) {
            case Canvas::CODE_RIGHT_ARROW:
                $this->x + 10;
                break;
            case Canvas::CODE_LEFT_ARROW:
                $this->x -10;
                break;
            case Canvas::CODE_UP_ARROW:
                $this->y + 10;
                break;
            case Canvas::CODE_DOWN_ARROW:
                $this->y -10;
                break;
            default:
                break; // todo exceptional case. maybe some work here in future
        }
        $block = new Block();
        $block->x = $this->x;
        $block->y = $this->y;
        return $block->isFree();
    }
}