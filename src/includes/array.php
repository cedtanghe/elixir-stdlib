<?php

namespace Elixir\STDLib;

if (!function_exists('\Elixir\STDLib\array_has')) {
    /**
     * @param int|string|array   $key
     * @param array|\ArrayAccess $data
     *
     * @return bool
     */
    function array_has($key, $data)
    {
        $segments = (array) $key;

        foreach ($segments as $segment) {
            if (!isset($data[$segment])) {
                return false;
            }

            $data = $data[$segment];
        }

        return true;
    }
}

if (!function_exists('\Elixir\STDLib\array_get')) {
    /**
     * @param int|string|array   $key
     * @param array|\ArrayAccess $data
     * @param mixed              $default
     *
     * @return mixed
     */
    function array_get($key, $data, $default = null)
    {
        $segments = (array) $key;

        foreach ($segments as $segment) {
            if (!isset($data[$segment])) {
                return is_callable($default) ? call_user_func($default) : $default;
            }

            $data = $data[$segment];
        }

        return $data;
    }
}

if (!function_exists('\Elixir\STDLib\array_set')) {
    /**
     * @param int|string|array   $key
     * @param mixed              $value
     * @param array|\ArrayAccess $data
     */
    function array_set($key, $value, &$data)
    {
        $segments = (array) $key;

        while (count($segments) > 1) {
            $segment = array_shift($segments);

            if (!isset($data[$segment])) {
                $data[$segment] = [];
            }

            $data = &$data[$segment];
        }

        $data[array_shift($segments)] = $value;
    }
}

if (!function_exists('\Elixir\STDLib\array_remove')) {
    /**
     * @param int|string|array   $key
     * @param array|\ArrayAccess $data
     */
    function array_remove($key, &$data)
    {
        $segments = (array) $key;

        while (count($segments) > 1) {
            $segment = array_shift($segments);

            if (!isset($data[$segment])) {
                return;
            }

            $data = &$data[$segment];
        }

        unset($data[array_shift($segments)]);
    }
}
