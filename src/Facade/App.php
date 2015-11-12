<?php

namespace Elixir\STDLib\Facade;

use Elixir\STDLib\FacadeTrait;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class I18N 
{
    use FacadeTrait;
    
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor() 
    {
        return 'Elixir\Foundation\ApplicationInterface';
    }
}
