<?php

namespace Elixir\STDLib;

use Elixir\STDLib\MacroableTrait;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class CSVUtils 
{
    use MacroableTrait;
    
    /**
     * @var string
     */
    const FORCE_UTF8 = "\xEF\xBB\xBF";

    /**
     * @param string $CSV
     * @param boolean $withHeaders
     * @param string $delimiter
     * @param string $enclosure
     * @return array
     */
    public static function CSVToArray($CSV, $withHeaders = false, $delimiter = ';', $enclosure = '"')
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

    /**
     * @param array $values
     * @param boolean $withHeaders
     * @param string $delimiter
     * @param string $enclosure
     * @return string
     */
    public static function arrayToCSV(array $values, $withHeaders = false, $delimiter = ';', $enclosure = '"')
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
