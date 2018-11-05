<?php
declare(strict_types=1);

namespace app;

use app\base\Canvas;

class Player extends Entity
{
    /**
     * Player constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        // todo add check free space
        $middle = Canvas::CANVAS_SIZE / 2;
//        $initWallCoordinateX = $middle - ((Wall::OFFSET + Player::SIZE_X + Wall::OFFSET + Wall::SIZE_X) /2);
        $initWallCoordinateX = $middle;
        $x = $initWallCoordinateX + Wall::SIZE_X + Player::OFFSET;
        $y = Canvas::CANVAS_SIZE - Player::SIZE_Y - Player::OFFSET;
        $block = new Block($x, $y, self::SIZE_X, self::SIZE_Y);
//        $block->isFree();
//        while (!$block->isFree()) {
//            $block->x = random_int(Canvas::CANVAS_START, Canvas::CANVAS_SIZE);
//            $block->y = random_int(Canvas::CANVAS_START, Canvas::CANVAS_SIZE);
//            // todo maybe some limit or exception
//        }
        $this->x = $block->x;
        $this->y = $block->y;
        $path = Map::getInstance()->path;
        $path->playerPosition = $block;

        $this->type = new Fighter(); // todo make customisable
    }

    public function makeMove(Move $move): void
    {
        // todo maybe check here
        $freeBlock = new Block($this->x, $this->y, self::SIZE_X, self::SIZE_Y);
        $path = Map::getInstance()->path;
        $path->addFreeBlocks($freeBlock);

        $this->x = $move->x;
        $this->y = $move->y;

        $block = new Block($this->x, $this->y, self::SIZE_X, self::SIZE_Y);
        $path = Map::getInstance()->path;
        $path->unsetFreeBlock($block);

        $path->playerPosition = $block;
    }
}