<?php
declare(strict_types=1);

namespace app;

class View
{
    /**
     * @var FullView | HalfView | FullView
     */
    public $view;
    public $mobView;
    public $previousDirection;
    public $direction;
    public $nextTurnDirection;

    public $initX = self::DEFAULT_INIT_X;
    public $initY = self::DEFAULT_INIT_Y;

    private const DEFAULT_INIT_X = 0;
    private const DEFAULT_INIT_Y = 0;

    public const FULL_VIEW = -1;
    public const FRONT_WALL_VIEW = 0;
    public const HALF_VIEW = 1;
    public const END_VIEW = 2;

    public const VIEWS = [
        self::FULL_VIEW => self::FULL_VIEW,
        self::HALF_VIEW => self::HALF_VIEW,
        self::END_VIEW => self::END_VIEW,
        self::FRONT_WALL_VIEW => self::FRONT_WALL_VIEW,
    ];

    public function __construct(int $viewType, int $direction, int $nextTurnDirection, Block $block, ?int $previousDirection = null)
    {
        $this->previousDirection = $previousDirection;
        $this->direction = $direction;
        $this->nextTurnDirection = $nextTurnDirection;
        if (\is_int($previousDirection)) {
            $nextTurnDirection = $direction;
            $direction = $previousDirection;
        }
        if ($viewType === self::FRONT_WALL_VIEW) {
            $this->view = new FrontWallView($direction, $nextTurnDirection);
        }
        if ($viewType === self::FULL_VIEW) {
            $this->view = new FullView($direction, $nextTurnDirection);
        }
        if ($viewType === self::HALF_VIEW) {
            $this->view = new HalfView($direction, $nextTurnDirection);
        }
        if ($viewType === self::END_VIEW) {
            $this->view = new EndView($direction, $nextTurnDirection);
        }
        $this->mobView = new MobView($viewType, $block);
    }
}