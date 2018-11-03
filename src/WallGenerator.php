<?php
declare(strict_types=1);

namespace app;

use app\base\Canvas;

class WallGenerator
{
    private const MIN_WALL_LENGTH = 3;
    private const DIRECTION_LEFT = 2;
    private const DIRECTION_UP = 1;
    private const DIRECTION_RIGHT = 3;
    private const DIRECTION_DOWN = 0;

    /**
     * @throws \Exception
     */
    public function chooseDirection($noDown = false): int
    {
        if ($noDown) {
            return random_int(self::DIRECTION_UP, self::DIRECTION_RIGHT);
        }
        return random_int(self::DIRECTION_DOWN, self::DIRECTION_RIGHT);
    }

    /**
     *  // todo to be easier no repeating directions
     * only left right or up by one chunk
     * @throws \Exception
     */
    public function simpleChooseDirection(int $previousDirection): int
    {
        if ($previousDirection === self::DIRECTION_UP) {
            return random_int(self::DIRECTION_LEFT, self::DIRECTION_RIGHT);
        }
        if ($previousDirection === self::DIRECTION_LEFT || self::DIRECTION_RIGHT) {
            return self::DIRECTION_UP;
        }
    }

    /**
     * @throws \Exception
     */
    public function generate(): void
    {

        $direction = self::DIRECTION_UP; // default
        $noDown = true; // default
        $middle = Canvas::CANVAS_SIZE / 2;
        $wallReg = WallRegistry::getInstance();
        $wallReg::unsetRegistry();
        $path = Map::getInstance()->path;

        // left side wall chunk
        $initWallCoordinateX = $middle; // todo make chunk class
        $initWallCoordinateY = Canvas::CANVAS_SIZE - Wall::SIZE_Y - Player::OFFSET;
        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY); // todo mayby make left and right class wall
        $wallReg::add($wall);
        for ($i=0;$i<2;$i++) {
            $initWallCoordinateY=$initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET;
            $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
            $wallReg::add($wall);
            $freeBlockX = $initWallCoordinateX + Wall::SIZE_X + Player::OFFSET;
            $freeBlockY = $initWallCoordinateY;
            $freeBlock = new Block($freeBlockX, $freeBlockY, Wall::SIZE_X, Wall::SIZE_Y);
            $path->addFreeBlocks(($freeBlock));
        }
        $lastLeftWall = $wall;
        // right side wall chunk
        $initWallCoordinateX = $middle + Wall::SIZE_X + Player::OFFSET + Player::SIZE_X + Player::OFFSET;
        $initWallCoordinateY = Canvas::CANVAS_SIZE - Wall::SIZE_Y - Player::OFFSET;
        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
        $wallReg::add($wall);
        for ($i=0;$i<2;$i++) {
            $initWallCoordinateY=$initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET;
            $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
            $wallReg::add($wall);
        }
        $lastRightWall = $wall;
        $previousDirection = $direction;
        for ($chunkQuantity = 0; $chunkQuantity < 10; $chunkQuantity++) {
            $direction = $this->simpleChooseDirection($previousDirection);
            if ($direction === $previousDirection) {
                continue; // todo to be easier no repeating directions
             }

            if ($previousDirection === self::DIRECTION_UP) {
                // todo in simple case this cannot happen. but already coded this, so keep it for future
                if ($direction === self::DIRECTION_UP) {
                    // left side wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX;
                    $initWallCoordinateY = $lastLeftWall->initY - Wall::SIZE_Y;
                    for ($i=0;$i<self::MIN_WALL_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateY -= Wall::SIZE_Y;
                    }
                    $lastLeftWall = $wall;
                    // right side wall chunk
                    $initWallCoordinateX = $lastRightWall->initX;
                    $initWallCoordinateY = $lastRightWall->initY - Wall::SIZE_Y;
                    for ($i=0;$i<self::MIN_WALL_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateY -= Wall::SIZE_Y;
                    }
                    $lastRightWall = $wall;
                    $previousDirection = $direction; // todo make this chunk property
                    $noDown = true;
                    continue;
                }

                if ($direction === self::DIRECTION_LEFT) {
                    // right side wall chunk
                    $initWallCoordinateX = $lastRightWall->initX;
                    $initWallCoordinateY = $lastRightWall->initY - Wall::SIZE_Y - (1 * Wall::OFFSET);
                    for ($i=0;$i<2;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateY = ($initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET);
                    }
                    $lastRightWall = $wall;
                    // top wall chunk
                    $initWallCoordinateX = $lastRightWall->initX - Wall::SIZE_X - (1 * Wall::OFFSET); // compensate offset
                    $initWallCoordinateY = $lastRightWall->initY;
                    for ($i=0;$i<4;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $freeBlockX = $initWallCoordinateX;
                        $freeBlockY = $initWallCoordinateY + Wall::SIZE_Y + Player::OFFSET;
                        $freeBlock = new Block($freeBlockX, $freeBlockY, Wall::SIZE_X, Wall::SIZE_Y);
                        $path->addFreeBlocks(($freeBlock));
                        $initWallCoordinateX = ($initWallCoordinateX-Wall::SIZE_X- Wall::OFFSET);
                    }
                    $lastRightWall = $wall;
                    // left side wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX - Wall::SIZE_X- Wall::OFFSET;
                    $initWallCoordinateY = $lastLeftWall->initY;
                    for ($i=0;$i<2;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateX = ($initWallCoordinateX-Wall::SIZE_X- Wall::OFFSET);
                    }
                    $lastLeftWall = $wall;
                    $previousDirection = $direction;
//                    $noDown = false;// todo to be easier no down direction
                    $noDown = true;
                    continue;
                }

                if ($direction === self::DIRECTION_RIGHT) {
                    // left side wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX;
                    $initWallCoordinateY = $lastLeftWall->initY - Wall::SIZE_Y - (1 * Wall::OFFSET);
                    for ($i=0;$i<2;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateY = ($initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET);
                    }
                    $lastLeftWall = $wall;
                    // top wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX + Wall::SIZE_X + (1 * Wall::OFFSET); // compensate offset
                    $initWallCoordinateY = $lastLeftWall->initY;
                    for ($i=0;$i<4;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $freeBlockX = $initWallCoordinateX;
                        $freeBlockY = $initWallCoordinateY + Wall::SIZE_Y + Player::OFFSET;
                        $freeBlock = new Block($freeBlockX, $freeBlockY, Wall::SIZE_X, Wall::SIZE_Y);
                        $path->addFreeBlocks(($freeBlock));
                        $initWallCoordinateX = ($initWallCoordinateX+Wall::SIZE_X+ Wall::OFFSET);
                    }
                    $lastLeftWall = $wall;
                    // bottom wall chunk
                    $initWallCoordinateX = $lastRightWall->initX + Wall::SIZE_X+ Wall::OFFSET;
                    $initWallCoordinateY = $lastRightWall->initY;
                    for ($i=0;$i<2;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateX = ($initWallCoordinateX+Wall::SIZE_X+ Wall::OFFSET); // todo make method with name
                    }
                    $lastRightWall = $wall;

                    $previousDirection = $direction;
//                    $noDown = false;// todo to be easier no down direction
                    $noDown = true;
                    continue;
                }
            }

            if ($previousDirection === self::DIRECTION_LEFT) {
                if ($direction === self::DIRECTION_UP) {
                    // bottom wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX - Wall::SIZE_X - (1 * Wall::OFFSET) ; // compensate previous offset
                    $initWallCoordinateY = $lastLeftWall->initY;
                    for ($i=0;$i<2;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateX = ($initWallCoordinateX-Wall::SIZE_X- Wall::OFFSET);
                    }
                    $lastLeftWall = $wall;
                    // left side wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX;
                    $initWallCoordinateY = $lastLeftWall->initY - Wall::SIZE_Y - (1 * Wall::OFFSET); // todo forget to save last wall
                    for ($i=0;$i<4;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $freeBlockX = $initWallCoordinateX + Wall::SIZE_X + Player::OFFSET;
                        $freeBlockY = $initWallCoordinateY;
                        $freeBlock = new Block($freeBlockX, $freeBlockY, Wall::SIZE_X, Wall::SIZE_Y);
                        $path->addFreeBlocks(($freeBlock));
                        $initWallCoordinateY = ($initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET);
                    }
                    $lastLeftWall = $wall;
                    // right wall chunk
                    $initWallCoordinateX = $lastRightWall->initX;
                    $initWallCoordinateY = $lastRightWall->initY - Wall::SIZE_Y- Wall::OFFSET;
                    for ($i=0;$i<2;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateY = ($initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET);
                    }
                    $lastRightWall = $wall;

                    $previousDirection = $direction; // todo make this chunk property
                    $noDown = true;
                    continue;
                }

            }

            if ($previousDirection === self::DIRECTION_RIGHT) {
                if ($direction === self::DIRECTION_UP) {
                    // bottom wall chunk
                    $initWallCoordinateX = $lastRightWall->initX + Wall::SIZE_X + (1 * Wall::OFFSET) ; // compensate previous offset
                    $initWallCoordinateY = $lastRightWall->initY;
                    for ($i=0;$i<2;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateX = ($initWallCoordinateX+Wall::SIZE_X+ Wall::OFFSET);
                    }
                    $lastRightWall = $wall;
                    // right wall chunk
                    $initWallCoordinateX = $lastRightWall->initX;
                    $initWallCoordinateY = $lastRightWall->initY - Wall::SIZE_Y - (1* Wall::OFFSET);
                    for ($i=0;$i<4;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $freeBlockX = $initWallCoordinateX - Wall::SIZE_X - Player::OFFSET;
                        $freeBlockY = $initWallCoordinateY;
                        $freeBlock = new Block($freeBlockX, $freeBlockY, Wall::SIZE_X, Wall::SIZE_Y);
                        $path->addFreeBlocks(($freeBlock));
                        $initWallCoordinateY = ($initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET);
                    }
                    $lastRightWall = $wall;
                    // left side wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX;
                    $initWallCoordinateY = $lastLeftWall->initY - Wall::SIZE_Y -  Wall::OFFSET; // todo forget to save last wall
                    for ($i=0;$i<2;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateY = ($initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET);
                    }
                    $lastLeftWall = $wall;


                    $previousDirection = $direction; // todo make this chunk property
                    $noDown = true;
                    continue;
                }

            }


            // todo to be easier no down direction
//            if ($direction === self::DIRECTION_DOWN) {
//
//                $noDown = false;
//                continue;
//            }
        }
    }



}