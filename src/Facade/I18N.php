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
     * @param string $message
     * @param array $options
     * @return string
     */
    public static function __($message, array $options = [])
    {
        $instance = static::resolveInstance(static::getFacadeAccessor());
        
        if (null === $instance)
        {
            return $message;
        }
        
        return $instance->translate($message, $options);
    }
}
