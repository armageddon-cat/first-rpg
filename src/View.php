<?php
declare(strict_types=1);

namespace app;

class View
{
    /**
     * @var FullView | HalfView | FullView
     */
    public $view;

    public $initX = self::DEFAULT_INIT_X;
    public $initY = self::DEFAULT_INIT_Y;

    private const DEFAULT_INIT_X = 0;
    private const DEFAULT_INIT_Y = 0;

    public const FULL_VIEW = 0;
    public const HALF_VIEW = 1;
    public const END_VIEW = 2;

    public const VIEWS = [
        self::FULL_VIEW,
        self::HALF_VIEW,
        self::END_VIEW,
    ];

    public function __construct(int $viewType, int $direction, int $nextDirection)
    {
        if ($viewType === self::FULL_VIEW) {
            $this->view = new FullView($direction, $nextDirection);
        }
        if ($viewType === self::HALF_VIEW) {
            $this->view = new HalfView($direction, $nextDirection);
        }
        if ($viewType === self::END_VIEW) {
            $this->view = new EndView($direction, $nextDirection);
        }
    }
}