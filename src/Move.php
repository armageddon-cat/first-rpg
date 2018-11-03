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
    private const MOVE_SIZE = 21;

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
                $this->x += self::MOVE_SIZE;
                break;
            case Canvas::CODE_LEFT_ARROW:
                $this->x -= self::MOVE_SIZE;
                break;
            case Canvas::CODE_UP_ARROW:
                $this->y -= self::MOVE_SIZE;
                break;
            case Canvas::CODE_DOWN_ARROW:
                $this->y += self::MOVE_SIZE;
                break;
            default:
                break; // todo exceptional case. maybe some work here in future
        }
        $this->block->x = $this->x;
        $this->block->y = $this->y;

        $isFreeBlock = $this->block->isFree();
        $hasMob = $this->block->hasMob();
        if ($hasMob) {
            $mob = Map::getInstance()->mobs;
            $mob->addDamage();
        }

        return $isFreeBlock && !$hasMob;
    }
}