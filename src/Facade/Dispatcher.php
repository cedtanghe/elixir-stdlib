<?php

namespace Elixir\STDLib\Facade;

use Elixir\STDLib\FacadeTrait;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class Dispatcher 
{
    use FacadeTrait;
    
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor() 
    {
        return 'Elixir\Dispatcher\DispatcherInterface';
    }
}
