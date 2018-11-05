<?php
declare(strict_types=1);

namespace app;

class Map
{
    /**
     * @var WallRegistry
     */
    public $walls;
    /**
     * @var Path
     */
    public $path;
    /**
     * @var Mob
     */
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

    /**
     * @throws \Exception
     */
    private function generateMobs(): void
    {
        $this->mobs = new Mob();
    }
}