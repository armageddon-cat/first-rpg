<?php
declare(strict_types=1);

namespace app;

use app\base\Canvas;

class Player
{
    public $type;
    public $x;
    public $y;

    public const SIZE_X = 20;
    public const SIZE_Y = 20;
    public const OFFSET = 1;

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
        $x = $initWallCoordinateX + Wall::SIZE_X + Wall::OFFSET + self::OFFSET;
        $y = Canvas::CANVAS_SIZE - Player::SIZE_Y- Player::OFFSET;
        $block = new Block($x, $y, self::SIZE_X, self::SIZE_Y);
//        $block->isFree();
//        while (!$block->isFree()) {
//            $block->x = random_int(Canvas::CANVAS_START, Canvas::CANVAS_SIZE);
//            $block->y = random_int(Canvas::CANVAS_START, Canvas::CANVAS_SIZE);
//            // todo maybe some limit or exception
//        }
        $this->x = $block->x;
        $this->y = $block->y;
        $this->type = new Fighter(); // todo make customisable
    }

    public function makeMove(Move $move)
    {
        // todo maybe check here
        $this->x = $move->x;
        $this->y = $move->y;
    }
}