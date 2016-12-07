<?php

namespace Elixir\STDLib;

if (!function_exists('\Elixir\STDLib\is_json')) {
    /**
     * @param mixed $object
     *
     * @return bool
     */
    function is_json($object)
    {
        try {
            return null !== $object && null !== json_decode($object);
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('\Elixir\STDLib\str_random')) {
    /**
     * @param int $length
     *
     * @return string
     */
    function str_random($length = 16)
    {
        $str = '';

        while (($len = strlen($str)) < $length) {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $str .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $str;
    }
}

if (!function_exists('\Elixir\STDLib\transliterate')) {
    /**
     * @param string               $str
     * @param \Transliterator|null $transliterator
     *
     * @return string
     */
    function transliterate($str, $transliterator = null)
    {
        if (class_exists('\Transliterator')) {
            $transliterator = $transliterator ?: \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC;');
            $str = $transliterator->transliterate($str);
        } else {
            static $cache = [];

            if (!array_key_exists($str, $cache)) {
                $base = $str;

                $str = htmlentities($str, ENT_NOQUOTES, 'utf-8');
                $str = preg_replace('/&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);/', '\1', $str);
                $str = preg_replace('/&([A-za-z]{2})(?:lig);/', '\1', $str);
                $str = preg_replace('/&[^;]+;/', '', $str);

                $cache[$base] = $str;
            } else {
                $str = $cache[$str];
            }
        }

        return $str;
    }
}

if (!function_exists('\Elixir\STDLib\camelize')) {
    /**
     * @param string $str
     *
     * @return string
     */
    function camelize($str)
    {
        static $cache = [];

        if (!array_key_exists($str, $cache)) {
            $base = $str;
            $str = preg_replace('/[^a-z0-9]+/i', '', ucwords(str_replace(['-', '_', '.'], ' ', $str)));
            $cache[$base] = $str;
        } else {
            $str = $cache[$str];
        }

        return $str;
    }
}

if (!function_exists('\Elixir\STDLib\slugify')) {
    /**
     * @param string $str
     * @param string $separator
     *
     * @return string
     */
    function slugify($str, $separator = '-')
    {
        static $cache = [];

        if (!array_key_exists($str, $cache)) {
            $base = $str;

            $str = preg_replace('/[^'.preg_quote($separator, '/').'\pL\pN\s]+/u', '', strtolower(transliterate($str)));
            $str = preg_replace('/['.preg_quote($separator == '-' ? '_' : '-', '/').']+/u', $separator, $str);
            $str = preg_replace('/['.preg_quote($separator, '/').'\s]+/u', $separator, $str);
            $str = trim($str, $separator);

            $cache[$base] = $str;
        } else {
            $str = $cache[$str];
        }

        return $str;
    }
}

if (!function_exists('\Elixir\STDLib\snake')) {
    /**
     * @param string $str
     * @param string $separator
     *
     * @return string
     */
    function snake($str, $separator = '-')
    {
        static $cache = [];

        if (!array_key_exists($str, $cache)) {
            $base = $str;
            $str = strtolower(preg_replace('/(.)([A-Z])/', '$1'.$separator.'$2', $str));
            $cache[$base] = $str;
        } else {
            $str = $cache[$str];
        }

        return $str;
    }
}

if (!function_exists('\Elixir\STDLib\text_summary')) {
    /**
     * @param string $str
     * @param int    $words
     * @param string $ellipsis
     *
     * @return string
     */
    function text_summary($str, $separator = '-')
    {
        $str = strip_tags($str);
        preg_match('/^\s*+(?:\S++\s*+){1,'.$words.'}/u', $str, $matches);

        if (!isset($matches[0]) || strlen($str) === strlen($matches[0])) {
            return $str;
        }

        return rtrim($matches[0]).$ellipsis;
    }
}
