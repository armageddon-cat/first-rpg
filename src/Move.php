<?php
declare(strict_types=1);

namespace app;

use app\base\Canvas;

class Move
{
    public $direction;
    public $x;
    public $y;
    /** @var Block */
    public $block;
    public const MOVE_SIZE = 21;

    public function __construct(ClientMessageContainer $container, Block $block)
    {
        $this->direction = $container->getData()->direction;
        $this->x = $block->x;
        $this->y = $block->y;
        $this->block = $block;
    }

    public function isAllowed(): bool
    {
        $player = Map::getInstance()->player;
        $playerDirection = $player->direction;
        $path = Map::getInstance()->path;
        $playerViewDirectionBlock = new Block($this->x, $this->y, Player::SIZE_X, Player::SIZE_Y);
        switch ($this->direction) {
            case Canvas::CODE_RIGHT_ARROW:
                if ($playerDirection === Canvas::CODE_RIGHT_ARROW) {
                    $playerViewDirectionBlock->y += Move::MOVE_SIZE;
                }
                if ($playerDirection === Canvas::CODE_LEFT_ARROW) {
                    $playerViewDirectionBlock->y -= Move::MOVE_SIZE;
                }
                if ($playerDirection === Canvas::CODE_UP_ARROW) {
                    $playerViewDirectionBlock->x += Move::MOVE_SIZE;
                }
                if ($playerDirection === Canvas::CODE_DOWN_ARROW) {
                    $playerViewDirectionBlock->x -= Move::MOVE_SIZE;
                }
                $playerViewDirectionBlockAllowed = $playerViewDirectionBlock->isFree();
                if ($playerViewDirectionBlockAllowed) {
                    // change direction only if we dont look on wall
                    $player->direction = $this->turnRight($playerDirection);
                    $view = Game::getInstance()->getViewByCoordinates($this->x, $this->y);
                    if ($view->view instanceof FrontWallView) {
                        $currentPlayerBlock = new Block($player->x, $player->y, Player::SIZE_X, Player::SIZE_Y);
                        $path->addViews($currentPlayerBlock, View::FULL_VIEW, $player->direction, $view->nextTurnDirection);
                    }
                }
                break;
            case Canvas::CODE_LEFT_ARROW:
                if ($playerDirection === Canvas::CODE_RIGHT_ARROW) {
                    $playerViewDirectionBlock->y -= Move::MOVE_SIZE;
                }
                if ($playerDirection === Canvas::CODE_LEFT_ARROW) {
                    $playerViewDirectionBlock->y += Move::MOVE_SIZE;
                }
                if ($playerDirection === Canvas::CODE_UP_ARROW) {
                    $playerViewDirectionBlock->x -= Move::MOVE_SIZE;
                }
                if ($playerDirection === Canvas::CODE_DOWN_ARROW) {
                    $playerViewDirectionBlock->x += Move::MOVE_SIZE;
                }
                $playerViewDirectionBlockAllowed = $playerViewDirectionBlock->isFree();
                if ($playerViewDirectionBlockAllowed) {
                    // change direction only if we dont look on wall
                    $player->direction = $this->turnLeft($playerDirection);
                    $view = Game::getInstance()->getViewByCoordinates($this->x, $this->y);
                    if ($view->view instanceof FrontWallView) {
                        $currentPlayerBlock = new Block($player->x, $player->y, Player::SIZE_X, Player::SIZE_Y);
                        $path->addViews($currentPlayerBlock, View::FULL_VIEW, $player->direction, $view->nextTurnDirection);
                    }
                }
                break;
            case Canvas::CODE_UP_ARROW:
                if ($playerDirection === Canvas::CODE_RIGHT_ARROW) {
                    $this->x += Move::MOVE_SIZE;
                }
                if ($playerDirection === Canvas::CODE_LEFT_ARROW) {
                    $this->x -= Move::MOVE_SIZE;
                }
                if ($playerDirection === Canvas::CODE_UP_ARROW) {
                    $this->y -= Move::MOVE_SIZE;
                }
                if ($playerDirection === Canvas::CODE_DOWN_ARROW) {
                    $this->y += Move::MOVE_SIZE;
                }
                break;
            case Canvas::CODE_DOWN_ARROW:
                if ($playerDirection === Canvas::CODE_RIGHT_ARROW) {
                    $this->x -= Move::MOVE_SIZE;
                }
                if ($playerDirection === Canvas::CODE_LEFT_ARROW) {
                    $this->x += Move::MOVE_SIZE;
                }
                if ($playerDirection === Canvas::CODE_UP_ARROW) {
                    $this->y += Move::MOVE_SIZE;
                }
                if ($playerDirection === Canvas::CODE_DOWN_ARROW) {
                    $this->y -= Move::MOVE_SIZE;
                }
                break;
        }
        $this->block->x = $this->x;
        $this->block->y = $this->y;

        $isFreeBlock = $this->block->isFree();
        $hasMob = $this->block->hasMob();
        if ($hasMob) {
            $mob = Map::getInstance()->mobs;
            $mob->addDamage();
        }

        return $isFreeBlock && !$hasMob;
    }

    private function turnLeft($currentDirection)
    {
        if ($currentDirection === Canvas::CODE_LEFT_ARROW) {
            return Canvas::CODE_DOWN_ARROW;
        }
        $newDirection = $currentDirection-1;

        return $newDirection;
    }

    private function turnRight($currentDirection)
    {
        if ($currentDirection === Canvas::CODE_DOWN_ARROW) {
            return Canvas::CODE_LEFT_ARROW;
        }
        $newDirection = $currentDirection+1;

        return $newDirection;
    }
}