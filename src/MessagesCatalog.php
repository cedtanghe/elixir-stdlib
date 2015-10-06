<?php

namespace Elixir\STDLib;

use Elixir\STDLib\ArrayUtils;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */

class MessagesCatalog
{
    /**
     * @var MessagesCatalog
     */
    protected static $instance;
    
    /**
     * @return MessagesCatalog
     */
    public static function instance()
    {
        if (null === static::$instance)
        {
            static::$instance = new static();
        }
        
        return static::$instance;
    }
    
    /**
     * @var array
     */
    protected $messages = [];
    
    /**
     * @param array $messages
     */
    public function __contruct(array $messages = [])
    {
        $this->messages += $messages;
    }
    
    /**
     * @param string|array $key
     * @return boolean
     */
    public function has($key)
    {
        return ArrayUtils::has($key, $this->messages);
    }

    /**
     * @param string|array $key
     * @param array $replacements
     * @param mixed $default
     * @return mixed
     */
    public function get($key, array $replacements = [], $default = null)
    {
        $message =  ArrayUtils::get($key, $this->messages, $default);
        
        if (is_string($message))
        {
            foreach ($replacements as $key => $value)
            {
                $message = str_replace($key, $value, $message);
            }
        }
        
        return $message;
    }

    /**
     * @param string|array $key
     * @param string|array $value
     */
    public function set($key, $value)
    {
        ArrayUtils::set($key, $value, $this->messages);
    }

    /**
     * @param string|array $key
     */
    public function remove($key)
    {
        ArrayUtils::remove($key, $this->messages);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->messages;
    }

    /**
     * @param array $data
     */
    public function replace(array $data)
    {
        $this->messages = $data;
    }

    /**
     * @param array|MessageCatalog
     * @param boolean $recursive
     */
    public function merge($data, $recursive = false)
    {
        if ($data instanceof self)
        {
            $data = $data->all();
        }
        
        $this->messages = $recursive ? array_merge_recursive($this->messages, $data) : array_merge($this->messages, $data);
    }
}
