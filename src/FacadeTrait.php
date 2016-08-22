<?php

namespace Elixir\STDLib;

use Elixir\DI\ContainerInterface;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
trait FacadeTrait
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
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        throw new \RuntimeException('Facade does not implement "getFacadeAccessor" method.');
    }

    /**
     * @param string|null $key
     *
     * @return mixed|null
     */
    public static function resolveInstance($key = null)
    {
        $key = $key ?: static::getFacadeAccessor();

        return static::$container->get($key, [], null);
    }

    /**
     * @ignore
     */
    public static function __callStatic($method, $arguments)
    {
        $instance = static::resolveInstance(static::getFacadeAccessor());

        return call_user_func_array([$instance, $method], $arguments);
    }
}
