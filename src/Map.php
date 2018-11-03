<?php
declare(strict_types=1);

namespace app;

class Map
{
    public $walls;
    public $path;
    public $mobs;
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
        self::setInstance($this);
        $this->generatePath();
        $this->generateWalls();
        $this->generatePlayer();
        $this->generateMobs();
    }

    /**
     * @throws \Exception
     */
    private function generateWalls(): void
    {
        $wallGenerator = new WallGenerator();
        $wallGenerator->generate();
        $this->walls = WallRegistry::getInstance();
    }

    /**
     * @throws \Exception
     */
    private function generatePlayer(): void
    {
        $this->player = new Player();
    }

    /**
     * @throws \Exception
     */
    private function generatePath(): void
    {
        $this->path = new Path();
    }
    private function generateMobs(): void
    {
        $this->mobs = new Mob();
    }

    public function prepareJsonToClient(): string
    {
        $result = [];
        foreach ($this->walls as $wall) {
            $result['walls'][] = ['x' => $wall->initX, 'y' => $wall->initY];
        }
        // dont need it on front
//        foreach ($this->path->freeBlock as $path) {
//            $result['path'][] = ['x' => $path->x, 'y' => $path->y];
//        }
        $result['player'] = ['x' => $this->player->x, 'y' => $this->player->y];
        if (!empty($this->mobs)) {
            $result['mobs'][] = ['x' => $this->mobs->x, 'y' => $this->mobs->y, 'hpSrc' => $this->mobs->hpSrc]; // todo now one. in future maybe more
        }
        return json_encode($result);
    }
}