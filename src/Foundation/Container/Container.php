<?php

namespace Vyui\Foundation\Container;

use Closure;
use TypeError;
use ReflectionClass;
use ReflectionParameter;
use ReflectionException;
use Vyui\Support\Helpers\_Reflect;
use Vyui\Contracts\Container as ContainerContract;
use Vyui\Exceptions\Container\BindingResolutionException;

class Container implements ContainerContract
{
    /**
     * The global access to the available container.
     *
     * @var Container
     */
    protected static Container $instance;

    /**
     * Container abstract bindings.
     *
     * @var array
     */
    protected array $bindings = [];

    /**
     * Container abstract bindings that are shared.
     *
     * @var array
     */
    protected array $instances = [];

    /**
     * Container abstractions that have been resolved.
     *
     * @var array
     */
    protected array $resolved = [];

    /**
     * Container abstractions that have been resolved along with a count of times that they've been resolved.
     *
     * @var array
     */
    protected array $resolvedCount = [];

    /**
     * Contextual building build stack override parameters.
     *
     * @var array
     */
    protected array $with = [];

    /**
     * Set the global access to the available container.
     *
     * @param Container|null $container
     * @return Container|static|null
     */
    public static function setInstance(self $container = null): Container|static|null
    {
        return static::$instance = $container;
    }

    /**
     * Get the global access to the available container.
     *
     * @return Container|static|null
     */
    public static function getInstance(): Container|static|null
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Resolve a particular Type from the container.
     *
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     *
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    public function make(string $abstract, array $parameters = []): mixed
    {
        return $this->resolve($abstract, $parameters);
    }

    /**
     * Bind a shared type to the container.
     *
     * @param string $abstract
     * @param string|Closure|null $concrete
     * @param bool $shared
     * @return void
     */
    public function singleton(string $abstract, string|Closure|null $concrete, bool $shared = true): void
    {
        $this->bind($abstract, $concrete, $shared);
    }

    /**
     * @param array $singletons
     * @return void
     */
    public function singletons(array $singletons): void
    {
        foreach ($singletons as $singleton) {
            $this->bind(...$singleton);
        }
    }

    /**
     * Bind a type to the container.
     *
     * @param string $abstract
     * @param string|Closure|null $concrete
     * @param bool $shared
     * @return void
     */
    public function bind(string $abstract, string|Closure|null $concrete, bool $shared = false): void
    {
        // if the concrete had been passed through as null, then we're going to want to make the concrete the same as
        // the abstraction. which will then concrete itself.
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        // check to see whether the concrete is of type Closure or not, and if not, we're going to wrap this up into a
        // factory Closure. so that we can later utilise this method for building out the bound type.
        if (! $concrete instanceof Closure) {
            if (! is_string($concrete) || preg_replace('/\d/', '', $concrete) === '') {
                throw new TypeError(
                    self::class . '::bind() argument #2 ($concrete) is not of type string|Closure|null'
                );
            }

            // assuming we've made it here, we can assume that the concretion is of course a string, a string of which
            // is a class name. Convert this into a factory method which will know how to resolve the abstraction.
            $concrete = function (Container $container, array $parameters = []) use ($abstract, $concrete): mixed {
                return $abstract === $concrete ? $container->build($concrete)
                                               : $container->resolve($concrete, $parameters);
            };
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    /**
     * Confirm an abstracted type has been bound to the container.
     *
     * @param string $abstract
     * @return bool
     */
    public function isBound(string $abstract): bool
    {
        return isset($this->bindings[$abstract]);
    }

    /**
     * Confirm an abstracted type has not yet been bound to the container.
     *
     * @param string $abstract
     * @return bool
     */
    public function isNotBound(string $abstract): bool
    {
        return ! $this->isBound($abstract);
    }

    /**
     * Flesh out and instantiate an abstracted concretion of the given type within the container.
     *
     * @param string|Closure $concrete
     * @return mixed
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    public function build(string|Closure $concrete): mixed
    {
        // if the concrete had been passed through as a Closure, we will just execute the closure and return early
        // handing back the results of the function, which allows functions to be utilised as resolvers.
        if ($concrete instanceof Closure) {
            return $concrete($this, []);
        }

        // attempt to reflect a particular concretion that we're dealing with. If we're incapable of reflecting the
        // class then it's safe to assume that the concrete class does not exist... and the container is going to be
        // incapable of building.
        try {
            $reflector = new ReflectionClass($concrete);
        } catch (ReflectionException $exception) {
            throw new BindingResolutionException("The target [$concrete] does not exist.", 0, $exception);
        }

        // check to see whether the class is instantiable, and if it isn't; then we're going to be returning early and
        // throwing an error, letting the developer know that this particular abstraction cannot be instantiated.
        // possible that an abstract class or an interface is attempting to be instantiated.
        if (! $reflector->isInstantiable()) {
            throw new BindingResolutionException("The target class [$concrete] is not instantiable.");
        }

        // if the constructor has been returned as null (empty) then no constructor is required for this abstraction
        // also meaning that there are no dependencies for the abstraction; so we're able to just return the concrete
        // as it stands.
        if (is_null($constructor = $reflector->getConstructor())) {
            return new $concrete;
        }

        // attempt to resolve the dependencies for the abstraction's constructor, and if we're unable to do this we're
        // going to need to throw an exception. Assuming we're able to get past this particular portion we can assume
        // the abstraction can now be whipped up and instantiated.
        try {
            $dependencies = $this->resolveDependencies($constructor->getParameters());
        } catch (BindingResolutionException $exception) {
            throw new $exception;
        }

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Resolve the abstraction from the container and return the results.
     *
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    public function resolve(string $abstract, array $parameters = []): mixed
    {
        // first things first, we are going to check whether the abstraction has already been instantiated and stored
        // in the containers instances. and if so, we're going to utilise it from there instead. and return early.
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $this->with[] = $parameters;

        // At this point, upon deciding whether the abstraction/concrete is buildable or not, we're ready to
        // create an instance of the concrete type that's been registered against the binding. This will  instantiate
        // the necessary types and any nested dependencies recursively until all has been resolved.
        if ($this->isBuildable($abstract, $concrete = $this->getConcrete($abstract))) {
            $abstraction = $this->build($concrete);
        } else {
            $abstraction = $this->make($concrete);
        }

        // if this particular binding had been marked as an item of which wants to be shared, then we're going to need
        // to store this in memory, so that upon requesting this abstraction again, we're going to be able to return it
        // once again, without the need for building out the abstraction again.
        if ($this->isShared($abstract)) {
            $this->instances[$abstract] = $abstraction;
        }

        // upon resolving the abstraction, we're going to mark the abstraction as resolved and begin counting how many
        // times the abstract has been resolved within the container. This is for debugging purposes.
        $this->markResolved($abstract);

        array_pop($this->with);

        return $abstraction;
    }

    /**
     * Mark a particular abstraction as resolved, increment a count of how many times an abstraction has been resolved
     * within the container.
     *
     * @param string $abstract
     * @return int
     */
    public function markResolved(string $abstract): int
    {
        $this->resolved[$abstract] = true;

        if (! isset($this->resolvedCount[$abstract])) {
            return $this->resolvedCount[$abstract] = 1;
        }

        return $this->resolvedCount[$abstract] += 1;
    }

    /**
     * Confirm if a given concrete against an abstraction is buildable.
     *
     * @param string $abstract
     * @param string|Closure|null $concrete
     * @return bool
     */
    public function isBuildable(string $abstract, string|Closure|null $concrete): bool
    {
        return $abstract === $concrete || $concrete instanceof Closure;
    }

    /**
     * Confirm if a given concrete against an abstraction is not buildable.
     *
     * @param string $abstract
     * @param string|Closure $concrete
     * @return bool
     */
    public function isNotBuildable(string $abstract, string|Closure $concrete): bool
    {
        return $abstract !== $concrete && ! $concrete instanceof Closure;
    }

    /**
     * Confirm whether a given type has been stated to be shared.
     *
     * @param string $abstract
     * @return bool
     */
    public function isShared(string $abstract): bool
    {
        return isset($this->bindings[$abstract]) && $this->bindings[$abstract]['shared'];
    }

    /**
     * Get the concrete for a particular abstraction.
     *
     * @param string $abstract
     * @return mixed
     */
    public function getConcrete(string $abstract): mixed
    {
        if (! empty($binding = $this->getBindings($abstract))) {
            return $binding[0]['concrete'];
        }

        return $abstract;
    }

    /**
     * Get all bindings or a dedicated binding depending on whether an abstraction is passed or not. either way this
     * method is designed to return an array, so it will be an array with one item or an array full of all items.
     *
     * @param string|null $abstract
     * @return array
     */
    public function getBindings(string|null $abstract = null): array
    {
        if (! is_null($abstract)) {
            return isset($this->bindings[$abstract])
                ? [$this->bindings[$abstract]]
                : [];
        }

        return $this->bindings;
    }

    /**
     * Set up an abstract instance into the container that we can re-use later as a shared resources. using this will
     * override any instance that would have previously been bound.
     *
     * @param string $abstract
     * @param mixed $instance
     * @return mixed
     */
    public function instance(string $abstract, mixed $instance): mixed
    {
        return $this->instances[$abstract] = $instance;
    }

    /**
     * Resolve the necessary dependencies for a given abstraction.
     *
     * @param ReflectionParameter[] $dependencies
     * @return array
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    private function resolveDependencies(array $dependencies): array
    {
        $results = [];

        foreach ($dependencies as $dependencyParameter) {
            if ($this->hasParameterOverride($dependencyParameter)) {
                $results[] = $this->getParameterOverride($dependencyParameter);
                continue;
            }

            // Run off and acquire the class, if this is null, then the dependency that we're dealing with is of course
            // a primitive type... if it's not a class, then we're not going to be able to resolve it, in which this
            // naturally would error out, to which we're then going to resolve the primitive type instead.
            $result = is_null(_Reflect::getParameterClassName($dependencyParameter))
                ? $this->resolvePrimitive($dependencyParameter)
                : $this->resolveClass($dependencyParameter);

            // if the dependency had been marked as variadic, then we're going to want to pass the variable in as an
            // array of items and continue to the next variable, otherwise ignore this block all together and insert
            // the necessary dependency.
            if ($dependencyParameter->isVariadic()) {
                $results = array_merge($results, $result);
                continue;
            }

            $results[] = $result;
        }

        return $results;
//        return array_map(function (ReflectionParameter $dependencyParameter) use ($dependencies) {
//            if ($this->hasParameterOverride($dependencyParameter)) {
//                return $this->getParameterOverride($dependencyParameter);
//            }
//
//            // Run off and acquire the class, if this is null, then the dependency that we're dealing with is of course
//            // a primitive type... if it's not a class, then we're not going to be able to resolve it, in which this
//            // naturally would error out, to which we're then going to resolve the primitive type instead.
//            $result = is_null(_Reflect::getParameterClassName($dependencyParameter))
//                ? $this->resolvePrimitive($dependencyParameter)
//                : $this->resolveClass($dependencyParameter);
//
//            // if the dependency had been marked as variadic, then we're going to want to pass the variable in as an
//            // array of items and continue to the next variable, otherwise ignore this block all together and insert the
//            // necessary dependency.
//
//            // if the dependency is variadic, then we're going to merge the variadic variable in with the dependencies.
//            return $dependencyParameter->isVariadic()
//                ? array_merge($dependencies, $result)
//                : $result;
//        }, $dependencies);
    }

    /**
     * @param ReflectionParameter $dependencyParameter
     * @return bool
     */
    private function hasParameterOverride(ReflectionParameter $dependencyParameter): bool
    {
        return array_key_exists($dependencyParameter->name, $this->getLastParameterOverride());
    }

    /**
     * @param ReflectionParameter $dependencyParameter
     * @return mixed
     */
    private function getParameterOverride(ReflectionParameter $dependencyParameter): mixed
    {
        return $this->getLastParameterOverride()[$dependencyParameter->name];
    }

    /**
     * @return array
     */
    private function getLastParameterOverride(): array
    {
        return count($this->with) ? end($this->with) : [];
    }

    /**
     * Resolve a non-class as a primitive dependency type.
     *
     * @param ReflectionParameter $dependency
     * @return mixed
     * @throws BindingResolutionException
     */
    private function resolvePrimitive(ReflectionParameter $dependency): mixed
    {
        if ($dependency->isDefaultValueAvailable()) {
            return $dependency->getDefaultValue();
        }

        throw new BindingResolutionException(
            "Unresolvable dependency resolving [$dependency] in class " .
            "[{$dependency->getDeclaringClass()->getName()}]"
        );
    }

    /**
     * Resolve a class dependency from the container.
     *
     * @param ReflectionParameter $dependency
     * @return mixed
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    private function resolveClass(ReflectionParameter $dependency): mixed
    {
        try {
            return $this->make(_Reflect::getParameterClassName($dependency));
        }

        // if we're incapable of instantiating the class, we can first find out if the value is optional and if it, we
        // can return those values instead.
        catch (BindingResolutionException $exception) {
            if ($dependency->isDefaultValueAvailable()) {
                array_pop($this->with);
                return $dependency->getDefaultValue();
            }

            if ($dependency->isVariadic()) {
                array_pop($this->with);
                return [];
            }

            throw $exception;
        }
    }

    /**
     * Get all the abstractions that have been resolved in the container.
     *
     * @param bool $withCount - get the resolved abstractions with a number of times abstractions have been resolved.
     * @return array
     */
    public function resolved(bool $withCount = false): array
    {
        if (! $withCount) {
            return $this->resolved;
        }

        $resolved = [];

        foreach ($this->resolved as $abstraction => $hasResolved) {
            $resolved[$abstraction] = [
                'resolved' => $hasResolved,
                'times' => $this->resolvedCount[$abstraction] ?? 0
            ];
        }

        return $resolved;
    }
}