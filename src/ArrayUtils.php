<?php

namespace Elixir\STDLib;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class ArrayUtils
{
    /**
     * @param mixed $key
     * @param array $data
     * @return boolean
     */
    public static function has($key, array $data)
    {
        $segments = (array)$key;
        $data = $data;

        foreach ($segments as $segment)
        {
            if (!is_array($data) || !array_key_exists($segment, $data))
            {
                return false;
            }

            $data = $data[$segment];
        }
        
        return true;
    }

    /**
     * @param mixed $key
     * @param array $data
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, array $data, $default = null) 
    {
        $segments = (array)$key;

        foreach ($segments as $segment)
        {
            if (!is_array($data) || !array_key_exists($segment, $data))
            {
                return is_callable($default) ? call_user_func($default) : $default;
            }

            $data = $data[$segment];
        }

        return $data;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @param array $data
     */
    public static function set($key, $value, array &$data)
    {
        $segments = (array)$key;

        while (count($segments) > 1)
        {
            $segment = array_shift($segments);

            if (!is_array($data) || !array_key_exists($segment, $data))
            {
                $data[$segment] = [];
            }

            $data = &$data[$segment];
        }

        $data[array_shift($segments)] = $value;
    }

    /**
     * @param mixed $key
     * @param array $data
     */
    public static function remove($key, array &$data)
    {
        $segments = (array)$key;

        while (count($segments) > 1)
        {
            $segment = array_shift($segments);

            if (!is_array($data) || !array_key_exists($segment, $data)) 
            {
                return;
            }

            $data = &$data[$segment];
        }

        unset($data[array_shift($segments)]);
    }
}
