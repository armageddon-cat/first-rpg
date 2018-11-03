<?php
declare(strict_types=1);

namespace app;

class Map
{
    public $walls;
    /**
     * @var Player
     */
    public $player;

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
        $this->generateWalls();
        $this->generatePlayer();
        self::setInstance($this);
    }

    /**
     * @throws \Exception
     */
    private function generateWalls(): void
    {
        $wallGenerator = new WallGenerator();
        $wallGenerator->generateWalls();
        $this->walls = WallRegistry::getInstance();
    }

    /**
     * @throws \Exception
     */
    private function generatePlayer(): void
    {
        $this->player = new Player();
    }

    public function prepareJsonToClient(): string
    {
        $result = [];
        foreach ($this->walls as $wall) {
            $result['walls'][] = ['x' => $wall->initX, 'y' => $wall->initY];
        }
        $result['player'] = ['x' => $this->player->x, 'y' => $this->player->y];
        return json_encode($result);
    }
}