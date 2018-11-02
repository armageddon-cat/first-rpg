<?php
declare(strict_types=1);

namespace app;

use app\base\Canvas;

class Move
{
    public $direction;
    public $x;
    public $y;
    /** @var Block */
    public $block;

    public function __construct(ClientMessageContainer $container, Block $block)
    {
        $this->direction = $container->getData()->direction;
        $this->x = $block->x;
        $this->y = $block->y;
        $this->block = $block;
    }

    public function isAllowed(): bool
    {
        switch ($this->direction) {
            case Canvas::CODE_RIGHT_ARROW:
                $this->x += 20;
                break;
            case Canvas::CODE_LEFT_ARROW:
                $this->x -= 20;
                break;
            case Canvas::CODE_UP_ARROW:
                $this->y -= 20;
                break;
            case Canvas::CODE_DOWN_ARROW:
                $this->y += 20;
                break;
            default:
                break; // todo exceptional case. maybe some work here in future
        }
        $this->block->x = $this->x;
        $this->block->y = $this->y;

        return $this->block->isFree();
    }
}