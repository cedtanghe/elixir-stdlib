<?php

namespace Elixir\STDLib\Facade;

use Elixir\HTTP\ServerRequestFactory;
use Elixir\HTTP\ServerRequestInterface;
use Elixir\STDLib\FacadeTrait;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Request
{
    use FacadeTrait;

    /**
     * @var ServerRequestInterface
     */
    protected static $serverRequest;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'Elixir\HTTP\ServerRequestInterface';
    }

    /**
     * {@inheritdoc}
     */
    public static function __callStatic($method, $arguments)
    {
        if (!static::$serverRequest) {
            static::$serverRequest = static::resolveInstance(static::getFacadeAccessor()) ?: ServerRequestFactory::createFromGlobals();
        }

        return call_user_func_array([static::$serverRequest, $method], $arguments);
    }
}
