<?php

namespace Vyui\Services\Database;

use Exception;
use Vyui\Support\Helpers\_String;

trait Getters
{
    /**
     * Get a value from the model that's stored within the attributes.
     *
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    public function get(string $key): mixed
    {
        if (! isset($this->attributes[$key])) {
            throw new Exception("$key not found against model: " . static::class);
        }

        return $this->attributes[$key];
    }

    /**
     * Polymorphically get a value from the model that's stored within the attributes.
     * This is going to transform the following: getFirstName into first_name and pass
     * first_name into the static::get() method which wants an attribute key (based on
     * database schema) this would be snakecase. 
     * 
     * @todo -> could this potentially be configurable depending on developer preference?
     *          or are we maintaining an opinionated bias against this? 
     *
     * @param string $method
     * @return mixed
     * @throws Exception
     */
    public function handlePolymorphicGetter(string $method): mixed
    {
        $polymorphicKey = _String::fromString($method)->remove('get')
                                                      ->snakecase()
                                                      ->toString();

        return $this->get($polymorphicKey);
    }
}
