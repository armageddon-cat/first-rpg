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
        foreach ($path->freeBlock as $pathBlock) {
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