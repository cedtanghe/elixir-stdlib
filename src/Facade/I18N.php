<?php

namespace Elixir\STDLib\Facade;

use Elixir\STDLib\FacadeTrait;

/**
 * @author Cédric Tanghe <ced.tanghe@gmail.com>
 */
class I18N
{
    use FacadeTrait;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'Elixir\I18N\I18NInterface';
    }

    /**
     * @param string $message
     * @param array  $options
     *
     * @return string
     */
    public static function __($message, array $options = [])
    {
        $instance = static::resolveInstance(static::getFacadeAccessor());

        if (null === $instance) {
            if (isset($options['%'])) {
                $message = str_replace(array_keys($options['%']), array_values($options['%']), $message);
            }

            return $message;
        }

        return $instance->translate($message, $options);
    }

    /**
     * @param string $singular
     * @param string $plural
     * @param int    $number
     * @param array  $options
     *
     * @return string
     */
    public static function _n($singular, $plural, $number, array $options = [])
    {
        $instance = static::resolveInstance(static::getFacadeAccessor());

        if (null === $instance) {
            $message = $number > 1 ? $plural : $singular;

            if (isset($options['%'])) {
                $message = str_replace(array_keys($options['%']), array_values($options['%']), $message);
            }

            return $message;
        }

        return $instance->translate($message, $options);
    }
}
