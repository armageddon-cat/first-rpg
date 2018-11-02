<?php
declare(strict_types=1);

namespace app;

use app\base\Canvas;

class Player
{
    public $type;
    public $x;
    public $y;

    /**
     * Player constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        // todo add check free space
        $block = new Block();
        $block->x = random_int(Canvas::CANVAS_START, Canvas::CANVAS_SIZE);
        $block->y = random_int(Canvas::CANVAS_START, Canvas::CANVAS_SIZE);
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