<?php
declare(strict_types=1);

namespace app;

class Game
{
    public $map;

    protected static $instance;

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public static function setInstance(self $instance): void
    {
        self::$instance = $instance;
    }

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        self::setInstance($this);
        $this->generateMap();
    }

    /**
     * @throws \Exception
     */
    private function generateMap()
    {
        $this->map = new Map();
    }

    private function getViews()
    {
        return $this->map->path->views;
    }

    public function getViewByCoordinates($x, $y): View
    {
        /** @var View[] $view */
        $views = $this->getViews();
        /** @var View $view */
        $view = $views[$x . ':' . $y];
        return $view;
    }

    public function prepareJsonToClient(): string
    {
        $result = [];
        $map = $this->map;
        foreach ($map->walls as $wall) {
            $result['walls'][] = ['x' => $wall->initX, 'y' => $wall->initY];
        }
        $result['player'] = ['x' => $map->player->x, 'y' => $map->player->y];
        /** @var View[] $view */
        $views = $this->getViews();
        /** @var View $view */
        $view = $this->getViewByCoordinates($map->player->x, $map->player->y);
        $result['gameView'] = ['x' => $view->view->initX, 'y' => $view->view->initY, 'src' => $view->view->src];
        if (!empty($map->mobs)) {
            $result['mobs'][] = ['x' => $map->mobs->x, 'y' => $map->mobs->y, 'src' => $map->mobs->hpSrc]; // todo now one. in future maybe more
            /** @var View $viewMob */
            $viewMob = $this->getViewByCoordinates($map->mobs->x, $map->mobs->y);
            if (!empty($viewMob->mobView->mobVisibleBlocks)) {
                foreach ($viewMob->mobView->mobVisibleBlocks as $mobVisibleBlocks) {
                    if ($mobVisibleBlocks->x === $map->player->x && $mobVisibleBlocks->y === $map->player->y) {
                        $result['mobsView'][] = ['x' => $viewMob->mobView->initX, 'y' => $viewMob->mobView->initY, 'src' => $viewMob->mobView->src];
                        break;
                    }
                }
            }

        }

        return json_encode($result);
    }
}