<?php

namespace Elixir\Util;

use Elixir\STDLib\MacroableTrait;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class StringUtils 
{
    use MacroableTrait;

    /**
     * @param mixed $object
     * @return boolean
     */
    public static function isJSON($object) 
    {
        try 
        {
            return null !== $object && null !== json_decode($object);
        } 
        catch (\Exception $e) 
        {
            return false;
        }
    }

    /**
     * @param integer $length
     * @param string|array $charlist
     * @return string
     */
    public static function random($length = 10, $charlist = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        if (is_array($charlist))
        {
            $charlist = implode('', $charlist);
        }

        return substr(str_shuffle($charlist), 0, $length);
    }

    /**
     * @param string $str
     * @param string $charset
     * @return string
     */
    public static function removeAccents($str, $charset = 'utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);
        $str = preg_replace('/&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);/', '\1', $str);
        $str = preg_replace('/&([A-za-z]{2})(?:lig);/', '\1', $str);
        $str = preg_replace('/&[^;]+;/', '', $str);

        return $str;
    }

    /**
     * @param string $str
     * @return string
     */
    public static function camelize($str)
    {
        return preg_replace('/[^a-z0-9]+/i', '', ucwords(str_replace(['-', '_', '.'], ' ', $str )));
    }

    /**
     * @param string $str
     * @param string $separator
     * @return string
     */
    public static function slug($str, $separator = '-') 
    {
        $str = preg_replace('/[^' . preg_quote($separator, '/') . '\pL\pN\s]+/u', '', strtolower(static::removeAccents($str)));
        $str = preg_replace('/[' . preg_quote($separator == '-' ? '_' : '-', '/') . ']+/u', $separator, $str);
        $str = preg_replace('/[' . preg_quote($separator, '/') . '\s]+/u', $separator, $str);

        return trim($str, $separator);
    }

    /**
     * @param string $str
     * @param string $delimiter
     * @return string
     */
    public static function snake($str, $delimiter = '-') 
    {
        return strtolower(preg_replace('/(.)([A-Z])/', '$1' . $delimiter . '$2', $str));
    }
    
    /**
     * @param string $str
     * @param integer $max
     * @param string $cut
     * @param boolean $useWord
     * @return string
     */
    public static function resume($str, $max = 100, $cut = '...', $useWord = true)
    {
        $str = strip_tags($str);

        if (strlen($str) > $max)
        {
            $to = $max - strlen($cut);
            $result = substr($str, 0, $to);

            if ($useWord)
            {
                $start = explode(' ', substr($str, 0, $to + 25));
                $end = explode(' ', $result);

                if (end($start) !== end($end)) 
                {
                    array_pop($end);
                    $result = implode(' ', $end);
                }
            }

            $str = rtrim($result) . $cut;
        }

        return $str;
    }
}
