<?php

namespace Elixir\STDLib;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
trait MacroableTrait 
{
    /**
     * @var array 
     */
    protected static $macros = [];

    /**
     * @param string $name
     * @param callable $macro
     */
    public static function macro($name, callable $macro)
    {
        static::$macros[$name] = $macro;
    }

    /**
     * @param string $name
     * @return boolean
     */
    public static function hasMacro($name) 
    {
        return isset(static::$macros[$name]);
    }
    
    /**
     * @ignore
     */
    public function __call($method, $arguments) 
    {
        return static::__callStatic($method, $arguments);
    }
    
    /**
     * @ignore
     * @throws \BadMethodCallException
     */
    public static function __callStatic($method, $arguments)
    {
        if (static::hasMacro($method)) 
        {
            return call_user_func_array(static::$macros[$method], $arguments);
        }

        throw new \BadMethodCallException(sprintf('Method "%s" is not available.', $method));
    }
}
