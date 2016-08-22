<?php

namespace Elixir\STDLib\Facade;

use Elixir\DI\ContainerInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class DI
{
    /**
     * @var ContainerInterface
     */
    protected static $container;

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
        static::$container = $value;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed|void
     */
    public static function __callStatic($method, $arguments)
    {
        return call_user_func_array([static::$container, $method], $arguments);
    }
}
