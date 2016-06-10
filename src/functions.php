<?php

namespace Elixir\STDLib;

/*
|--------------------------------------------------------------------------
| ARRAY UTILITIES
|--------------------------------------------------------------------------
*/

if (!function_exists('\Elixir\STDLib\array_has'))
{
    /**
     * @param int|string|array $key
     * @param array|\ArrayAccess $data
     * @return boolean
     */
    function array_has($key, $data)
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
}

if (!function_exists('\Elixir\STDLib\array_get'))
{
    /**
     * @param int|string|array $key
     * @param array|\ArrayAccess $data
     * @param mixed $default
     * @return mixed
     */
    function array_get($key, $data, $default = null)
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
}

if (!function_exists('\Elixir\STDLib\array_set'))
{
    /**
     * @param int|string|array $key
     * @param mixed $value
     * @param array|\ArrayAccess $data
     */
    function array_set($key, $value, &$data)
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
}

if (!function_exists('\Elixir\STDLib\array_remove'))
{
    /**
     * @param int|string|array $key
     * @param array|\ArrayAccess $data
     */
    function array_remove($key, &$data)
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

/*
|--------------------------------------------------------------------------
| CSV UTILITIES
|--------------------------------------------------------------------------
*/

if (!defined('CSV_FORCE_UTF8'))
{
    define('CSV_FORCE_UTF8', "\xEF\xBB\xBF");
}

if (!function_exists('\Elixir\STDLib\csv_to_array'))
{
    /**
     * @param string $CSV
     * @param boolean $withHeaders
     * @param string $delimiter
     * @param string $enclosure
     * @return array
     */
    function csv_to_array($CSV, $withHeaders = false, $delimiter = ';', $enclosure = '"')
    {
        $parsed = [];
        $lines = [];

        if (is_file($CSV))
        {
            if (false !== ($handle = fopen($CSV, 'r'))) 
            {
                while (($line = fgetcsv($handle, 4096, $delimiter, $enclosure)) !== false)
                {
                    $lines[] = $line;
                }

                fclose($handle);
            }
        } 
        else 
        {
            $lines = str_getcsv($CSV, $delimiter, $enclosure);
        }

        $i = 0;
        $linesLength = count($lines);
        $names = [];

        for ($i = 0; $i < $linesLength; ++$i)
        {
            $line = $lines[$i];
            $lineLentgh = count($line);

            for ($j = 0; $j < $lineLentgh; ++$j)
            {
                if ($withHeaders) 
                {
                    if ($i === 0)
                    {
                        $names[] = $line[$j];
                    } 
                    else 
                    {
                        $parsed[$i][$names[$j]] = $line[$j];
                    }
                } 
                else 
                {
                    $parsed[$i][] = $line[$j];
                }
            }
        }

        return $parsed;
    }
}

if (!function_exists('\Elixir\STDLib\array_to_csv'))
{
    /**
     * @param array $values
     * @param boolean $withHeaders
     * @param string $delimiter
     * @param string $enclosure
     * @return string
     */
    function array_to_csv(array $values, $withHeaders = false, $delimiter = ';', $enclosure = '"')
    {
        $CSV = '';

        if (count($values) > 0)
        {
            if ($withHeaders) 
            {
                $columns = [];
                $work = [];

                foreach ($values[0] as $key => $value)
                {
                    $columns[] = $key;
                }

                $work[0] = $columns;
                $i = 1;

                foreach ($values as $data) 
                {
                    foreach ($columns as $column)
                    {
                        foreach ($data as $key => $value)
                        {
                            if ($key === $column)
                            {
                                $work[$i][] = $value;
                                break;
                            }
                        }
                    }

                    ++$i;
                }
            } 
            else 
            {
                $work = $values;
            }

            $fd = tmpfile();

            foreach ($work as $value)
            {
                fputcsv($fd, $value, $delimiter, $enclosure);
            }

            rewind($fd);

            $CSV = stream_get_contents($fd);
            fclose($fd);
        }

        return $CSV;
    }
}

/*
|--------------------------------------------------------------------------
| STRING UTILITIES
|--------------------------------------------------------------------------
*/

if (!function_exists('\Elixir\STDLib\is_json'))
{
    /**
     * @param mixed $object
     * @return boolean
     */
    function is_json($object)
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
}

if (!function_exists('\Elixir\STDLib\str_random'))
{
    /**
     * @param  integer  $length
     * @return string
     */
    function str_random($length = 16)
    {
        $str = '';
        
        while (($len = strlen($str)) < $length)
        {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $str .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }
        
        return $str;
    }
}

if (!function_exists('\Elixir\STDLib\transliterate'))
{
    /**
     * @param string $str
     * @param \Transliterator|null $transliterator
     * @return string
     */
    function transliterate($str, $transliterator = null)
    {
        if (class_exists('\Transliterator'))
        {
            $transliterator = $transliterator ?: \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC;');
            $str = $transliterator->transliterate($str);
        }
        else
        {
            static $cache = [];
            
            if (!array_key_exists($str, $cache))
            {
                $base = $str;
                
                $str = htmlentities($str, ENT_NOQUOTES, 'utf-8');
                $str = preg_replace('/&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);/', '\1', $str);
                $str = preg_replace('/&([A-za-z]{2})(?:lig);/', '\1', $str);
                $str = preg_replace('/&[^;]+;/', '', $str);
                
                $cache[$base] = $str;
            }
            else
            {
                $str = $cache[$str];
            }
        }
        
        return $str;
    }
}

if (!function_exists('\Elixir\STDLib\camelize'))
{
    /**
     * @param string $str
     * @return string
     */
    function camelize($str)
    {
        static $cache = [];
        
        if (!array_key_exists($str, $cache))
        {
            $base = $str;
            $str = preg_replace('/[^a-z0-9]+/i', '', ucwords(str_replace(['-', '_', '.'], ' ', $str)));
            $cache[$base] = $str;
        }
        else
        {
            $str = $cache[$str];
        }
        
        return $str;
    }
}

if (!function_exists('\Elixir\STDLib\slugify'))
{
    /**
     * @param string $str
     * @param string $separator
     * @return string
     */
    function slugify($str, $separator = '-')
    {
        static $cache = [];
        
        if (!array_key_exists($str, $cache))
        {
            $base = $str;
            
            $str = preg_replace('/[^' . preg_quote($separator, '/') . '\pL\pN\s]+/u', '', strtolower(transliterate($str)));
            $str = preg_replace('/[' . preg_quote($separator == '-' ? '_' : '-', '/') . ']+/u', $separator, $str);
            $str = preg_replace('/[' . preg_quote($separator, '/') . '\s]+/u', $separator, $str);
            $str = trim($str, $separator);
            
            $cache[$base] = $str;
        }
        else
        {
            $str = $cache[$str];
        }

        return $str;
    }
}

if (!function_exists('\Elixir\STDLib\snake'))
{
    /**
     * @param string $str
     * @param string $separator
     * @return string
     */
    function snake($str, $separator = '-')
    {
        static $cache = [];
        
        if (!array_key_exists($str, $cache))
        {
            $base = $str;
            $str = strtolower(preg_replace('/(.)([A-Z])/', '$1' . $separator . '$2', $str));
            $cache[$base] = $str;
        }
        else
        {
            $str = $cache[$str];
        }

        return $str;
    }
}

if (!function_exists('\Elixir\STDLib\text_summary'))
{
    /**
     * @param string $str
     * @param integer $words
     * @param string $ellipsis
     * @return string
     */
    function text_summary($str, $separator = '-')
    {
        $str = strip_tags($str);
        preg_match('/^\s*+(?:\S++\s*+){1,' . $words . '}/u', $str, $matches);

        if (!isset($matches[0]) || strlen($str) === strlen($matches[0]))
        {
            return $str;
        }

        return rtrim($matches[0]) . $ellipsis;
    }
}
