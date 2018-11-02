<?php
declare(strict_types=1);

namespace app\base;

abstract class AbstractRegistry extends \ArrayIterator
{
    protected static $instance;
    
    /**
     * @return static
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::setInstance(new static);
        }

        return static::$instance;
    }
    
    public static function setInstance(\ArrayIterator $instance): void
    {
        static::$instance = $instance;
    }
    
    public static function add(PointAbstract $point): void
    {
        static::getInstance()->offsetSet($point->getId(), $point);
    }
        
    public static function exists(string $id): bool
    {
        return static::getInstance()->offsetExists($id);
    }
    
    public static function remove(PointAbstract $point): void
    {
        static::getInstance()->offsetUnset($point->getId());
    }
    
    public static function unsetRegistry(): void
    {
        static::$instance = null;
    }
}
