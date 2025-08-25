<?php

namespace Vyui\Support\Helpers;

use Closure;
use ReflectionClass;
use ReflectionMethod;
use ReflectionFunction;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;

class _Reflect
{
    /**
     * If possible, we're going to collect the class name of the given parameter's type.
     *
     * @param ReflectionParameter $reflectionParameter
     * @return string|null
     */
    public static function getParameterClassName(ReflectionParameter $reflectionParameter): ?string
    {
        if (! ($type = $reflectionParameter->getType()) instanceof ReflectionNamedType || $type->isBuiltin()) {
            return null;
        }

        $name = $type->getName();

        if (($class = $reflectionParameter->getDeclaringClass()) !== null) {
            if ($name === 'self') {
                return $class->getName();
            }

            if ($name === 'parent' && ($parent = $class->getParentClass())) {
                return $parent->getName();
            }
        }

        return $name;
    }

    /**
     * A Helper method to get a classes' method's parameter names, which will be utilised for autowiring the necessary
     * variable names to a particular class upon building.
     *
     * @param object $class
     * @param string|null $method
     * @return array
     */
    public static function getClassMethodParameterNames(object $class, ?string $method = null): array
    {
        $parameters = $method !== null
            ? (new ReflectionClass($class))->getMethod($method)->getParameters()
            : (new ReflectionFunction($class))->getParameters();

        return array_flip(array_map(fn ($item) => $item->getName(), $parameters));
    }

    /**
     * A method which will return an array keyed by the name of the variable along with some various information
     * pieces that might be useful along the way, right now all this will be returning is type which will be the
     * type definition that would/should have been defined in the method or action specified.
     *
     * @param object $class
     * @param string|null $method
     * @return array
     */
    public static function getClassMethodParameterInfo(object $class, ?string $method = null): array
    {
        $parameters = $method !== null
            ? (new ReflectionClass($class))->getMethod($method)->getParameters()
            : (new ReflectionFunction($class))->getParameters();

        $return = [];

        foreach ($parameters as $parameter) {
            $return[$parameter->getName()] = [
                'type' => $parameter->getType()?->getName() ?? null
            ];
        }

        return $return;
    }

    /**
     * @param string|object $class
     * @return array
     * @throws ReflectionException
     */
    public static function getClassMethods(string|object $class): array
    {
        return (new ReflectionClass($class))->getMethods();
    }

    /**
     * @param string $contains
     * @return Closure
     */
    public static function filetMethodsWhereContains(string $contains): Closure
    {
        return fn (ReflectionMethod $method) => str_contains($method, $contains);
    }

    /**
     * Make a Reflectable class from a class name or the class object itself; and return the reflectable so that
     * further contaminations can be performed on that class.
     *
     * @param string|object $class
     * @return Reflectable
     */
    public static function fromClass(string|object $class): Reflectable
    {
        return new Reflectable($class);
    }

    /**
     * @param string|object $class
     * @return string
     * @throws ReflectionException
     */
    public static function getClassShortName(string|object $class): string
    {
        return (new ReflectionClass($class))->getShortName();
    }
}
