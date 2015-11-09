<?php

namespace Elixir\STDLib\Facade;

use Elixir\DB\DBInterface;
use Elixir\STDLib\FacadeTrait;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class DB 
{
    use FacadeTrait;
    
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor() 
    {
        return 'Elixir\DB\DBInterface';
    }
    
    /**
     * @param string $name
     * @return DBInterface|null
     */
    public static function with($name)
    {
        return static::resolveInstance($name);
    }
}
