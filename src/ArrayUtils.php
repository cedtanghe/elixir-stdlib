<?php

namespace Elixir\STDLib;

use Elixir\STDLib\MacroableTrait;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */

class ArrayUtils
{
    use MacroableTrait;
    
    /**
     * @param int|string|array $key
     * @param array|\ArrayAccess $data
     * @return boolean
     */
    public static function has($key, $data)
    {
        $segments = (array)$key;

        foreach ($segments as $segment)
        {
            if (!isset($data[$segment]))
            {
                return false;
            }

            $data = $data[$segment];
        }
        
        return true;
    }

    /**
     * @param int|string|array $key
     * @param array|\ArrayAccess $data
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $data, $default = null) 
    {
        $segments = (array)$key;

        foreach ($segments as $segment)
        {
            if (!isset($data[$segment]))
            {
                return is_callable($default) ? call_user_func($default) : $default;
            }

            $data = $data[$segment];
        }

        return $data;
    }

    /**
     * @param int|string|array $key
     * @param mixed $value
     * @param array|\ArrayAccess $data
     */
    public static function set($key, $value, &$data)
    {
        $segments = (array)$key;

        while (count($segments) > 1)
        {
            $segment = array_shift($segments);

            if (!isset($data[$segment]))
            {
                $data[$segment] = [];
            }

            $data = &$data[$segment];
        }

        $data[array_shift($segments)] = $value;
    }

    /**
     * @param int|string|array $key
     * @param array|\ArrayAccess $data
     */
    public static function remove($key, &$data)
    {
        $segments = (array)$key;

        while (count($segments) > 1)
        {
            $segment = array_shift($segments);

            if (!isset($data[$segment]))
            {
                return;
            }

            $data = &$data[$segment];
        }

        unset($data[array_shift($segments)]);
    }
}
