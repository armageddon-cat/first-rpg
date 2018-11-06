<?php
declare(strict_types=1);

namespace app;

use app\base\Canvas;

class WallGenerator
{
    private const WALL_STRAIGHT_LENGTH = 3;
    private const WALL_TURN_STRAIGHT_LENGTH = 2;
    private const WALL_TURN_TURN_LENGTH = 1;
    // todo smth to refactor
    public const DIRECTION_LEFT = Canvas::CODE_LEFT_ARROW;
    public const DIRECTION_UP = Canvas::CODE_UP_ARROW;
    public const DIRECTION_RIGHT = Canvas::CODE_RIGHT_ARROW;
    public const DIRECTION_DOWN = Canvas::CODE_DOWN_ARROW;

    /**
     *  // todo to be easier no repeating directions
     * only left right or up by one chunk
     * @throws \Exception
     */
    public function simpleChooseDirection(int $previousDirection): int
    {
        if ($previousDirection === self::DIRECTION_UP) {
            $dirChooser = random_int(0, 1);
            if ($dirChooser === 0) {
                return self::DIRECTION_LEFT;
            }
            return self::DIRECTION_RIGHT;
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
        $middle = Canvas::CANVAS_SIZE / 2;
        $wallReg = WallRegistry::getInstance();
        $wallReg::unsetRegistry();
        $path = Map::getInstance()->path;

        $previousDirection = $direction;
        $nextDirection = $this->simpleChooseDirection($previousDirection);

        // left side wall chunk
        $initWallCoordinateX = $middle; // todo make chunk class
        $initWallCoordinateY = Canvas::CANVAS_SIZE - Wall::SIZE_Y - Player::OFFSET;
        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY); // todo maybe make left and right class wall
        $wallReg::add($wall);
        $playerBlockX = $initWallCoordinateX + Wall::SIZE_X + Player::OFFSET;
        $playerBlockY = $initWallCoordinateY;
        $playerBlock = new Block($playerBlockX, $playerBlockY, Wall::SIZE_X, Wall::SIZE_Y);
        $path->addViews($playerBlock, View::FULL_VIEW, $direction, $nextDirection);
        for ($i=1;$i<self::WALL_STRAIGHT_LENGTH;$i++) {
            $initWallCoordinateY=$initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET;
            $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
            $wallReg::add($wall);
            $freeBlockX = $initWallCoordinateX + Wall::SIZE_X + Player::OFFSET;
            $freeBlockY = $initWallCoordinateY;
            $freeBlock = new Block($freeBlockX, $freeBlockY, Wall::SIZE_X, Wall::SIZE_Y);
            $path->addFreeBlocks($freeBlock);
            $path->addViews($freeBlock, View::VIEWS[$i], $direction, $nextDirection);
        }
        $lastLeftWall = $wall;
        // right side wall chunk
        $initWallCoordinateX = $middle + Wall::SIZE_X + Player::OFFSET + Player::SIZE_X + Player::OFFSET;
        $initWallCoordinateY = Canvas::CANVAS_SIZE - Wall::SIZE_Y - Player::OFFSET;
        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
        $wallReg::add($wall);
        for ($i=1;$i<self::WALL_STRAIGHT_LENGTH;$i++) {
            $initWallCoordinateY=$initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET;
            $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
            $wallReg::add($wall);
        }
        $lastRightWall = $wall;

        for ($chunkQuantity = 0; $chunkQuantity < 10; $chunkQuantity++) {
            $direction = $nextDirection;
            $nextDirection = $this->simpleChooseDirection($direction);
            if ($direction === $previousDirection || $direction === $nextDirection) {
                continue; // todo to be easier no repeating directions
             }

            if ($previousDirection === self::DIRECTION_UP) {
                if ($direction === self::DIRECTION_LEFT) {
                    // right side wall chunk
                    $initWallCoordinateX = $lastRightWall->initX;
                    $initWallCoordinateY = $lastRightWall->initY - Wall::SIZE_Y - (1 * Wall::OFFSET);
                    for ($i=0;$i<self::WALL_TURN_STRAIGHT_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateY = ($initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET);
                    }
                    $lastRightWall = $wall;
                    // top wall chunk
                    $initWallCoordinateX = $lastRightWall->initX - Wall::SIZE_X - (1 * Wall::OFFSET); // compensate offset
                    $initWallCoordinateY = $lastRightWall->initY;
                    for ($i=0;$i<self::WALL_STRAIGHT_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $freeBlockX = $initWallCoordinateX;
                        $freeBlockY = $initWallCoordinateY + Wall::SIZE_Y + Player::OFFSET;
                        $freeBlock = new Block($freeBlockX, $freeBlockY, Wall::SIZE_X, Wall::SIZE_Y);
                        $path->addFreeBlocks($freeBlock);
                        if ($i === 0) {
                            $path->addViews($freeBlock, View::VIEWS[$i], $direction, $nextDirection, $previousDirection);
                        } else {
                            $path->addViews($freeBlock, View::VIEWS[$i], $direction, $nextDirection);
                        }
                        $initWallCoordinateX = ($initWallCoordinateX-Wall::SIZE_X- Wall::OFFSET);
                    }
                    $lastRightWall = $wall;
                    // bottom side wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX - Wall::SIZE_X- Wall::OFFSET;
                    $initWallCoordinateY = $lastLeftWall->initY;
                    for ($i=0;$i<self::WALL_TURN_TURN_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateX = ($initWallCoordinateX-Wall::SIZE_X- Wall::OFFSET);
                    }
                    $lastLeftWall = $wall;
                    $previousDirection = $direction;
                    continue;
                }

                if ($direction === self::DIRECTION_RIGHT) {
                    // left side wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX;
                    $initWallCoordinateY = $lastLeftWall->initY - Wall::SIZE_Y - (1 * Wall::OFFSET);
                    for ($i=0;$i<self::WALL_TURN_STRAIGHT_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateY = ($initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET);
                    }
                    $lastLeftWall = $wall;
                    // top wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX + Wall::SIZE_X + (1 * Wall::OFFSET); // compensate offset
                    $initWallCoordinateY = $lastLeftWall->initY;
                    for ($i=0;$i<self::WALL_STRAIGHT_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $freeBlockX = $initWallCoordinateX;
                        $freeBlockY = $initWallCoordinateY + Wall::SIZE_Y + Player::OFFSET;
                        $freeBlock = new Block($freeBlockX, $freeBlockY, Wall::SIZE_X, Wall::SIZE_Y);
                        $path->addFreeBlocks($freeBlock);
                        if ($i === 0) {
                            $path->addViews($freeBlock, View::VIEWS[$i], $direction, $nextDirection, $previousDirection);
                        } else {
                            $path->addViews($freeBlock, View::VIEWS[$i], $direction, $nextDirection);
                        }
                        $initWallCoordinateX = ($initWallCoordinateX+Wall::SIZE_X+ Wall::OFFSET);
                    }
                    $lastLeftWall = $wall;
                    // bottom wall chunk
                    $initWallCoordinateX = $lastRightWall->initX + Wall::SIZE_X+ Wall::OFFSET;
                    $initWallCoordinateY = $lastRightWall->initY;
                    for ($i=0;$i<self::WALL_TURN_TURN_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateX = ($initWallCoordinateX+Wall::SIZE_X+ Wall::OFFSET); // todo make method with name
                    }
                    $lastRightWall = $wall;

                    $previousDirection = $direction;
                    continue;
                }
            }

            if ($previousDirection === self::DIRECTION_LEFT) {
                if ($direction === self::DIRECTION_UP) {
                    // bottom wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX - Wall::SIZE_X - (1 * Wall::OFFSET) ; // compensate previous offset
                    $initWallCoordinateY = $lastLeftWall->initY;
                    for ($i=0;$i<self::WALL_TURN_STRAIGHT_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateX = ($initWallCoordinateX-Wall::SIZE_X- Wall::OFFSET);
                    }
                    $lastLeftWall = $wall;
                    // left side wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX;
                    $initWallCoordinateY = $lastLeftWall->initY - Wall::SIZE_Y - (1 * Wall::OFFSET); // todo forget to save last wall
                    for ($i=0;$i<self::WALL_STRAIGHT_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $freeBlockX = $initWallCoordinateX + Wall::SIZE_X + Player::OFFSET;
                        $freeBlockY = $initWallCoordinateY;
                        $freeBlock = new Block($freeBlockX, $freeBlockY, Wall::SIZE_X, Wall::SIZE_Y);
                        $path->addFreeBlocks($freeBlock);
                        if ($i === 0) {
                            $path->addViews($freeBlock, View::VIEWS[$i], $direction, $nextDirection, $previousDirection);
                        } else {
                            $path->addViews($freeBlock, View::VIEWS[$i], $direction, $nextDirection);
                        }
                        $initWallCoordinateY = ($initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET);
                    }
                    $lastLeftWall = $wall;
                    // right wall chunk
                    $initWallCoordinateX = $lastRightWall->initX;
                    $initWallCoordinateY = $lastRightWall->initY - Wall::SIZE_Y- Wall::OFFSET;
                    for ($i=0;$i<self::WALL_TURN_TURN_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateY = ($initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET);
                    }
                    $lastRightWall = $wall;

                    $previousDirection = $direction; // todo make this chunk property
                    continue;
                }

            }

            if ($previousDirection === self::DIRECTION_RIGHT) {
                if ($direction === self::DIRECTION_UP) {
                    // bottom wall chunk
                    $initWallCoordinateX = $lastRightWall->initX + Wall::SIZE_X + (1 * Wall::OFFSET) ; // compensate previous offset
                    $initWallCoordinateY = $lastRightWall->initY;
                    for ($i=0;$i<self::WALL_TURN_STRAIGHT_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateX = ($initWallCoordinateX+Wall::SIZE_X+ Wall::OFFSET);
                    }
                    $lastRightWall = $wall;
                    // right wall chunk
                    $initWallCoordinateX = $lastRightWall->initX;
                    $initWallCoordinateY = $lastRightWall->initY - Wall::SIZE_Y - (1* Wall::OFFSET);
                    for ($i=0;$i<self::WALL_STRAIGHT_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $freeBlockX = $initWallCoordinateX - Wall::SIZE_X - Player::OFFSET;
                        $freeBlockY = $initWallCoordinateY;
                        $freeBlock = new Block($freeBlockX, $freeBlockY, Wall::SIZE_X, Wall::SIZE_Y);
                        $path->addFreeBlocks($freeBlock);
                        if ($i === 0) {
                            $path->addViews($freeBlock, View::VIEWS[$i], $direction, $nextDirection, $previousDirection);
                        } else {
                            $path->addViews($freeBlock, View::VIEWS[$i], $direction, $nextDirection);
                        }
                        $initWallCoordinateY = ($initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET);
                    }
                    $lastRightWall = $wall;
                    // left side wall chunk
                    $initWallCoordinateX = $lastLeftWall->initX;
                    $initWallCoordinateY = $lastLeftWall->initY - Wall::SIZE_Y -  Wall::OFFSET; // todo forget to save last wall
                    for ($i=0;$i<self::WALL_TURN_TURN_LENGTH;$i++) {
                        $wall = new Wall($initWallCoordinateX, $initWallCoordinateY);
                        $wallReg::add($wall);
                        $initWallCoordinateY = ($initWallCoordinateY-Wall::SIZE_Y- Wall::OFFSET);
                    }
                    $lastLeftWall = $wall;


                    $previousDirection = $direction; // todo make this chunk property
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