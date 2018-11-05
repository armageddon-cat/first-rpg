<?php
declare(strict_types=1);

namespace app;

class Path
{
    public $freeBlock = [];
    public $playerPosition;
    public $mobsPosition;
    public $views = [];

    public function addFreeBlocks(Block $freeBlock): void
    {
        $this->freeBlock[] = $freeBlock;
    }

    public function unsetFreeBlock(Block $block): void
    {
        foreach ($this->freeBlock as $freeBlockIndex => $freeBlock) {
            if ($freeBlock->x === $block->x && $freeBlock->y === $block->y) {
                unset($this->freeBlock[$freeBlockIndex]);
                break;
            }
        }
    }

    public function addViews(Block $block, int $viewType, int $direction, int $nextDirection): void
    {
        $this->views[$block->x . ':' . $block->y] = new View($viewType, $direction, $nextDirection);
    }
}