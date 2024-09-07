<?php

namespace Vyui\Foundation;

trait Instanceable
{
    static protected static $instance;

    /**
    * Set the global access to the available container.
    *
    * @param static $container
    * @return static
    */
    public static function setInstance($container = null): static
    {
        return static::$instance = $container;
    }

    /**
    * Get the global access to the available container.
    *
    * @return self
    */
    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}
