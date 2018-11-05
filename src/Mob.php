<?php
declare(strict_types=1);

namespace app;

class Mob extends Entity
{
    public $hp;
    public $hpSrc;

    private const FULL_HP = 3;
    private const HALF_HP = 2;
    private const LAST_HP = 1;
    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $path = Map::getInstance()->path;
        foreach ($path->freeBlock as $pathBlockKey => $pathBlock) {
            $isStop = random_int(2, 12);
            if ($isStop === 2) {
                break;
            }
        }
        $path = Map::getInstance()->path;
        $path->mobsPosition = $pathBlock;

        $path = Map::getInstance()->path;
        $path->unsetFreeBlock($pathBlock);

        $this->x = $pathBlock->x;
        $this->y = $pathBlock->y;
        $this->type = new MobJaws(); // todo make customisable
        $this->hp = self::FULL_HP;
        $this->hpSrc = 'src/img/mob_jaws.jpg';

        $views = Map::getInstance()->path->views;
        $viewMob = $views[$this->x . ':' . $this->y];

        // todo some error on cacl
        if ($pathBlockKey%3 === 0) {
            $viewMob->mobView->mobVisibleBlocks[] = $path->freeBlock[$pathBlockKey-1];
            $viewMob->mobView->mobVisibleBlocks[] = $path->freeBlock[$pathBlockKey-2];
            $viewMob->mobView->mobVisibleBlocks[] = $path->freeBlock[$pathBlockKey-3];
        }
        if ($pathBlockKey%3 === 1) {
            $viewMob->mobView->mobVisibleBlocks[] = $path->freeBlock[$pathBlockKey-1];
            $viewMob->mobView->mobVisibleBlocks[] = $path->freeBlock[$pathBlockKey-2];
        }
        if ($pathBlockKey%3 === 2) {
            $viewMob->mobView->mobVisibleBlocks[] = $path->freeBlock[$pathBlockKey-1];
        }

    }

    public function addDamage(): void
    {
        if ($this->hp > 0) {
            $this->hp = $this->hp - 1;
            if ($this->hp === 0) {
                Map::getInstance()->mobs = null;
                $freeBlock = new Block($this->x, $this->y, self::SIZE_X, self::SIZE_Y);
                $path = Map::getInstance()->path;
                $path->addFreeBlocks($freeBlock);
            }
            if ($this->hp === self::HALF_HP) {
                $this->hpSrc = 'src/img/mob_jaws_half.jpg';
            }
            if ($this->hp === self::LAST_HP) {
                $this->hpSrc = 'src/img/mob_jaws_last.jpg';
            }
        }
    }
}