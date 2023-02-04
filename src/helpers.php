<?php

use Vyui\Contracts\Config\Config;
use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;
use Vyui\Services\View\ViewManager;
use Vyui\Foundation\Container\Container;
use Vyui\Contracts\Environment\Environment;

if (! function_exists('app')) {
    /**
     * Acquire either the application OR an abstraction from the application's container.
     *
     * @param string|null $abstract
     * @param array $parameters
     * @return mixed
     */
    function app(?string $abstract = null, array $parameters = []): mixed {
        return $abstract !== null ? Container::getInstance()->make($abstract, $parameters)
                                  : Container::getInstance();
    }
}

if (! function_exists('dd')) {
    /**
     * A method of which will be utilised for dumping variables on the page, as well as dying so that the application
     * will continue no further.
     *
     * @param ...$variables
     * @return void
     */
    function dd(...$variables): void {
        dump($variables);
        die();
    }
}

if (! function_exists('dump')) {
    /**
     * A method which will be utilised for dumping variables on a page.
     *
     * @param ...$variables
     * @return void
     */
    function dump(...$variables): void {
        echo '<div style="word-break: break-word; word-wrap: break-word">';
        foreach ($variables as $variable) {
            echo '<pre style="padding: 20px; border-radius: 4px; background-color: #f1f1f1;">';
                var_dump($variable);
            echo '</pre>';
        }
        echo '</div>';
    }
}

if (! function_exists('env')) {
    /**
     * Acquire variables from the .env file, a helper method of which assists the developer interacting with the .env
     * file attached to the application.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    function env(string $key, mixed $default = null): mixed {
        return app(Environment::class)->get($key, $default);
    }
}

if (! function_exists('config')) {
    /**
     * Acquire variables from the config files. A helper method of which assists the developer interacting with the
     * config variables attached to the application.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    function config(string $key, mixed $default = null): mixed {
        return app(Config::class)->get($key, $default);
    }
}

if (! function_exists('view')) {
    /**
     * Attempt to render a view.
     *
     * @param string $template
     * @param array $data
     * @return Response
     */
    function view(string $template, array $data = []): Response {
        return app(ViewManager::class)->resolve($template, $data);
    }
}

if (! function_exists('request')) {
    /**
     * Get the instance of the current request or an input item from the request.
     *
     * @param $key
     * @param $default
     * @return mixed
     */
    function request($key = null, $default = null): mixed {
        return ! $key ? app(Request::class)
                      : app(Request::class)->get($key, $default);
    }
}

if (! function_exists('asset')) {
    /**
     * Get the asset url of the application, mainly utilised for getting any asset that might reside within the
     * public namespace.
     *
     * @param string $uri
     * @param int|null $version
     * @return string
     */
    function asset(string $uri, ?int $version = null): string {
        $extra = $version ? "?version=$version" : '';
        return env('APP_URL') . "/{$uri}{$extra}";
    }
}

if (! function_exists('response')) {
    /**
     * Return a response in a simpler way rather than instantiating a response object each location a response is
     * needed. A helper utility method for simplifying the process.
     *
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return Response
     */
    function response(string $content = '', int $status = 200, array $headers = []): Response {
        return new Response($content, $status, $headers);
    }
}

if (! function_exists('types')) {
    /**
     * This method is designed to take in values such like:
     * [1, "1", [1, 2, 3]] and return...
     * [integer, string, array] so this method would be a helper function in oder to return the types in the same
     * structure as the array that was provided to it.
     *
     * @param ...$values
     * @return array
     */
    function types(...$values): array {
        return array_map(fn ($value) => gettype($value), $values);
    }
}