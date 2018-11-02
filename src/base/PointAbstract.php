<?php
declare(strict_types=1);

namespace app\base;

abstract class PointAbstract
{
    protected $id;
    protected $x;
    protected $y;
    
    public function getId(): string
    {
        return $this->id;
    }
    
    protected function setId(string $id): void
    {
        $this->id = $id;
    }
    
    public function getX(): int
    {
        return $this->x;
    }
    
    protected function setX(int $x): void
    {
        $this->x = $x;
    }
    
    public function getY(): int
    {
        return $this->y;
    }
    
    protected function setY(int $y): void
    {
        $this->y = $y;
    }
}
