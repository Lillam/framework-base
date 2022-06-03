<?php

namespace Vyui\Support;

use ReflectionClass;
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

        if (! is_null($class = $reflectionParameter->getDeclaringClass())) {
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
}