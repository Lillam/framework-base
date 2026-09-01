<?php

namespace Vyui\Services\Environment;

use Vyui\Services\Service;

class EnvironmentService extends Service
{
    private array $store = [];

    private string $path {
        get => $this->application->getBasePath('/.env');
    }

    public function register(): void
    {
        // boostrap the environment, acquire all the information into this and then register
        // it into the application layer.
        $this->bootstrap();

        $this->application->instance(self::class, $this);
    }

    /**
     * Bootstrap the environment, acquire all the information into this and then register
     * it into the application layer. Acquire all the environment variables from the .env
     * file within the root of the project.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        if (! file_exists($this->path)) {
            return;
        }

        // get the file contents from the .env file, this will be getting all the text
        // values from the env file; so in this instance we're going to need to strip
        // out all the lines that start with a # as this would be a comment... more
        // comment notions could be supported within the .env schema however this is
        // the only one for now.
        $variables = array_filter(
            explode("\n", $this->getEnvFileContents()),
            fn ($value) => ! \str_starts_with($value, "#")
        );

        foreach ($variables as $variable) {
            $this->set(...explode('=', $variable, 2));
        }
    }

    /**
     * Set an environment variable. this will go through a parser to figure out what data
     * type it wants to be. If the value is not a string then will be set as such without
     * parsing. Otherwise if it's a string it'll be parsed and casted into the appropriate
     * type that it wants to be.
     *
     * "NULL" | "null" -> null
     * "TRUE" | "true" -> true
     * "FALSE" | "false" -> false
     * "1" -> 1
     * "1.5" -> 1.5
     * "[1, 2, 3]" -> [1, 2, 3]
     * "["example", "test", "case"] -> ["example", "test", "case"]
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->store[$key] = $this->parseValue($value);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->store[$key] ?? $default;
    }

    private function getEnvFileContents(): string
    {
        return (string) preg_replace("/\n+/", "\n", trim((string) file_get_contents($this->path), "\n"), -1);
    }

    private function parseValue(mixed $value): mixed
    {
        // if the value is not a string, then we're dealing with a non string value
        // meaning we won't need to parse it and can just return the value as is.
        if (! \is_string($value)) {
            return $value;
        }

        // parse the value and figure out what type of data this wants to be within
        // the store. We *could* just leave this as a string and then allow the
        // developer later down the line to convert it to whatever data-type they want
        // however I think it's better for this to just be parsed into the correct
        // type as it's being stored into the env store.
        return match (true) {
            strtolower($value) === "true" => true,
            strtolower($value) === "false" => false,
            strtolower($value) === "null" => null,
            preg_match('/^\d+$/', $value, $_) === 1 => (int) $value,
            preg_match('/^\d+\.\d+$/', $value, $_) === 1 => (float) $value,
            preg_match('/^\[.*\]$/', $value, $_) === 1 => array_map(
                $this->parseValue(...),
                explode(', ', str_replace(['[', ']'], '', $value))
            ),
            default => $value
        };
    }

    public function all(): array
    {
        return $this->store;
    }

    /**
    * re-register the environment, useful for when the environment changes and needs
    * to be reloaded. This will trigger the whole service to be reloaded, rebuilt and
    * re-placed into the application.
    *
    * @return void
    */
    public function reload(): void
    {
        $this->register();
    }
}
