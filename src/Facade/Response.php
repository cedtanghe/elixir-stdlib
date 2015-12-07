<?php

namespace Elixir\STDLib\Facade;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Response 
{
    /**
     * @ignore
     */
    public static function __callStatic($method, $arguments) 
    {
        return call_user_func_array(['\Elixir\HTTP\ResponseFactory', $method], $arguments);
    }
}
