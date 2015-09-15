<?php

namespace Elixir\STDLib;

use Elixir\I18N\I18NInterface;
use Elixir\STDLib\ArrayUtils;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */

class MessagesCatalog
{
    /**
     * @var array
     */
    protected $messages = [];
    
    /**
     * @var I18NInterface
     */
    protected $translator;
    
    /**
     * @param array $messages
     */
    public function __contruct(array $messages = [])
    {
        $this->messages += $messages;
    }
    
    /**
     * @param I18NInterface $value
     */
    public function setTranslator(I18NInterface $value)
    {
        $this->translator = $value;
    }
    
    /**
     * @return I18NInterface
     */
    public function getTranslator()
    {
        return $this->translator;
    }
    
    /**
     * @see I18NInterface::translate()
     */
    public function translate($message, array $options = [])
    {
        if ($this->translator)
        {
            return $this->translator->translate($message, $options);
        }
        
        return $message;
    }
    
    /**
     * @param string $message
     * @return string
     */
    public static function __($message)
    {
        return $message;
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
