<?php

namespace Elixir\STDLib\Facade;

use Elixir\Cache\CacheInterface;
use Elixir\STDLib\FacadeTrait;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Cache
{
    use FacadeTrait;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'Elixir\Cache\CacheInterface';
    }

    /**
     * @param string $name
     *
     * @return CacheInterface|null
     */
    public static function with($name)
    {
        return static::resolveInstance($name);
    }
}
