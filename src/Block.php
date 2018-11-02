<?php
declare(strict_types=1);

namespace app;

class Block
{
    public $x;
    public $y;

    public function isFree()
    {
        return $this->isWall();
    }

    private function isWall(): bool
    {
        // todo optimize
        $walls = WallRegistry::getInstance();
        foreach ($walls as $wall) {
            $collisionX = false;
            for ($x = $wall->initX; $x <= $wall->initX + Wall::SIZE_X; $x++) {
                if ($x === $this->x) {
                    $collisionX = true;
                    break;
                }
            }
            $collisionY = false;
            for ($x = $wall->initY; $x <= $wall->initY + Wall::SIZE_Y; $x++) {
                if ($x === $this->y) {
                    $collisionY = true;
                    break;
                }
            }
            if ($collisionX || $collisionY) {
                return true;
            }
        }

        return false;
    }
}