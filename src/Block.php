<?php
declare(strict_types=1);

namespace app;

class Block
{
    public $x;
    public $y;
    public $sizeX;
    public $sizeY;
    private $coordinates;

    public function __construct($x, $y, $sizeX, $sizeY)
    {
        $this->x = $x;
        $this->y = $y;
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
    }

    public function isFree(): bool
    {
        $this->setBlockMatrix();
        return !$this->isWall();
    }

    public function hasMob(): bool
    {
        $mob = Map::getInstance()->mobs;
        if (empty($mob)) {
            return false;
        }
        if ($mob->x === $this->x && $mob->y === $this->y) {
            return true;
        }
        return false;
    }

    private function isWall(): bool
    {
        // todo optimize
        $walls = WallRegistry::getInstance();

        foreach ($walls as $wall) {
            $collision = false;
            $wallMaxX = $wall->initX + Wall::SIZE_X;
            $wallMaxY = $wall->initY + Wall::SIZE_Y;
            for ($x = $wall->initX; $x <= $wallMaxX; $x++) {
                for ($y = $wall->initY; $y <= $wallMaxY; $y++) {
                    $wallCoordinates = $x . ':' . $y;
                    if (isset($this->coordinates[$wallCoordinates])) {
                        $collision = true;
                        break;
                    }
                }
            }

            if ($collision) {
                return true;
            }
        }

        return false;
    }

    public function setBlockMatrix(): void
    {
        $blockMaxX = $this->x + $this->sizeX;
        $blockMaxY = $this->y + $this->sizeY;
        for ($bx = $this->x; $bx <= $blockMaxX; $bx++) {
            for ($by = $this->y; $by <= $blockMaxY; $by++) {
                $this->coordinates[$bx . ':' . $by] = true;
            }
        }
    }
}