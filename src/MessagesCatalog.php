<?php

namespace Elixir\STDLib;

use function Elixir\STDLib\array_get;
use function Elixir\STDLib\array_has;
use function Elixir\STDLib\array_remove;
use function Elixir\STDLib\array_set;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class MessagesCatalog implements \ArrayAccess, \Iterator, \Countable
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
        if (null === static::$instance) {
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
     *
     * @return bool
     */
    public function has($key)
    {
        return array_has($key, $this->messages);
    }

    /**
     * @param string|array $key
     * @param array        $replacements
     * @param mixed        $default
     *
     * @return mixed
     */
    public function get($key, array $replacements = [], $default = null)
    {
        $message = array_get($key, $this->messages, $default);

        if (is_string($message)) {
            foreach ($replacements as $key => $value) {
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
        array_set($key, $value, $this->messages);
    }

    /**
     * @param string|array $key
     */
    public function remove($key)
    {
        array_remove($key, $this->messages);
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
     * {@inheritdoc}
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function offsetSet($key, $value)
    {
        if (null === $key) {
            throw new \InvalidArgumentException('The key can not be undefined.');
        }

        $this->set($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return reset($this->messages);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->get(key($this->messages));
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->messages);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->messages);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return null !== key($this->messages);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->messages);
    }

    /**
     * @param array|MessageCatalog
     */
    public function merge($data)
    {
        if ($data instanceof self) {
            $data = $data->all();
        }

        $this->messages = array_merge($this->messages, $data);
    }

    /**
     * @ignore
     */
    public function __debugInfo()
    {
        return $this->messages;
    }
}
