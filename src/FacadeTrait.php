<?php

namespace Elixir\Facade;

use Elixir\DI\ContainerInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
trait FacadeAbstract 
{
    /**
     * @var ContainerInterface
     */
    protected static $container;

    /**
     * @var array
     */
    protected static $resolvedInstances = [];

    /**
     * @return ContainerInterface
     */
    public static function getContainer() 
    {
        return static::$container;
    }

    /**
     * @return ContainerInterface
     */
    public static function setContainer(ContainerInterface $value)
    {
        static::$_container = $value;
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor() 
    {
        throw new \RuntimeException('Facade does not implement "getFacadeAccessor" method.');
    }
    
    /**
     * @param string $key
     * @return mixed|null
     */
    public static function resolveInstance($key)
    {
        if (null === static::$container)
        {
            // Todo
        }

        return static::$container->get($key, [], null);
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed|void
     */
    public static function __callStatic($method, $arguments) 
    {
        $instance = static::resolveInstance(static::getFacadeAccessor());
        return call_user_func_array([$instance, $method], $arguments);
    }
}
